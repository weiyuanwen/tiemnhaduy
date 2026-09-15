# Histbank QR Reverb Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Nối Tiệm Nhà Duy Laravel với histbank: dán link Facebook → đơn + VietQR → một poller khớp CK → Reverb + email.

**Architecture:** Laravel là nguồn sự thật của đơn pending. Histbank chỉ expose `GET /transactions` trên Docker network nội bộ. Scheduler gọi poller: idle thì 0 request bank; có đơn thì một lần fetch cho mọi user, matcher regex `ORDFB` + đúng số tiền + CRDT, rồi `OrderService::confirmBankMatch` (lock + dedupe). Reverb bắn `payment.success` như event hiện có.

**Tech Stack:** Laravel 12, PHPUnit, histbank `tpbank-serve` (Node), VietQR image URL, Laravel Reverb, Docker Compose, Cloudflare tunnel `homelab`.

## Global Constraints

- Không poll TPBank khi không có `service_orders.status=pending`.
- N user pending = 1 lần `GET /transactions` mỗi tick đủ điều kiện.
- Query histbank: `days=1`, `pageSize=100`.
- Matcher: CRDT + `\bORDFB[A-Z0-9]{10}\b` + `amount` đúng bằng đơn.
- Cửa sổ thanh toán 12 phút (`config('payment.window_minutes')`).
- Trễ 20 giây trước fetch đầu; adaptive 25s / 40s / 60s; 401/423/429 backoff 2→5→15 phút.
- `HISTBANK_URL` từ container = `http://tpbank-serve:3999`, không `127.0.0.1`.
- Không commit `.env`, cookie, TPBank password.
- `verify-payment` không public; `markPaidTest` tắt production.
- Copy tiếng Việt cho mail/UI; code comment English như repo hiện tại.
- Prefix mã CK production: `ORDFB` + 10 ký tự (đúng `OrderService::createOrder` hiện tại).

## File map

- Create: `app/Services/Bank/BankTransferMatcher.php` — thuần khớp tx ↔ orders.
- Create: `app/Services/Bank/HistbankClient.php` — HTTP client.
- Create: `app/Services/Bank/PendingBankPoller.php` — idle/delay/backoff/orchestration.
- Create: `app/Services/VietQrService.php` — URL QR.
- Create: `app/Console/Commands/PollPendingBankTransfers.php`
- Create: `app/Console/Commands/ReconcileOvernightBankTransfers.php`
- Create: `app/Mail/ServiceOrderPaidMail.php` + view
- Create: `app/Listeners/SendServiceOrderPaidMail.php`
- Create: `database/migrations/2026_09_15_000001_add_customer_email_to_service_orders_table.php`
- Create: `tests/Unit/BankTransferMatcherTest.php`
- Create: `tests/Feature/PendingBankPollerTest.php`
- Create: `tests/Feature/VietQrOrderCreateTest.php`
- Modify: `app/Services/OrderService.php` — 12 phút, email, VietQR payload, `confirmBankMatch`
- Modify: `app/Http/Controllers/Api/OrderController.php` — email optional; khóa verify-payment
- Modify: `app/Models/ServiceOrder.php` + factory
- Modify: `config/services.php` — histbank, vietqr, payment
- Modify: `bootstrap/app.php` — schedule
- Modify: `app/Providers/EventServiceProvider.php` — mail listener
- Modify: `deploy/docker-compose.yml` — reverb + extra network
- Modify: `.env.example`

---

### Task 1: BankTransferMatcher

**Files:**
- Create: `tests/Unit/BankTransferMatcherTest.php`
- Create: `app/Services/Bank/BankTransferMatcher.php`

**Interfaces:**
- Consumes: mảng transaction histbank (`id`, `description`, `amount`, `creditDebitIndicator`); collection `ServiceOrder` pending.
- Produces: `BankTransferMatcher::match(array $transactions, iterable $pendingOrders): array<int, array{order: ServiceOrder, txId: string, amount: int, description: string}>`

- [ ] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\ServiceOrder;
use App\Services\Bank\BankTransferMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankTransferMatcherTest extends TestCase
{
    use RefreshDatabase;

    private function order(string $code, int $amount = 100000): ServiceOrder
    {
        return ServiceOrder::factory()->create([
            'order_code' => $code,
            'amount' => $amount,
            'status' => ServiceOrder::STATUS_PENDING,
            'service_id' => Service::factory(),
        ]);
    }

    private function tx(array $override): array
    {
        return array_merge([
            'id' => 'tx-1',
            'description' => 'ORDFBABCDEFGH12 ca phe',
            'amount' => 100000,
            'creditDebitIndicator' => 'CRDT',
        ], $override);
    }

    public function test_matches_credit_with_code_and_exact_amount(): void
    {
        $order = $this->order('ORDFBABCDEFGH12');
        $matches = (new BankTransferMatcher())->match([
            $this->tx([]),
        ], collect([$order]));

        $this->assertCount(1, $matches);
        $this->assertSame($order->id, $matches[0]['order']->id);
        $this->assertSame('tx-1', $matches[0]['txId']);
    }

    public function test_ignores_debit(): void
    {
        $order = $this->order('ORDFBABCDEFGH12');
        $matches = (new BankTransferMatcher())->match([
            $this->tx(['creditDebitIndicator' => 'DBIT']),
        ], collect([$order]));

        $this->assertSame([], $matches);
    }

    public function test_ignores_wrong_amount(): void
    {
        $order = $this->order('ORDFBABCDEFGH12', 100000);
        $matches = (new BankTransferMatcher())->match([
            $this->tx(['amount' => 99000]),
        ], collect([$order]));

        $this->assertSame([], $matches);
    }

    public function test_requires_word_boundary_on_code(): void
    {
        $order = $this->order('ORDFBABCDEFGH12');
        $matches = (new BankTransferMatcher())->match([
            $this->tx(['description' => 'XORDFBABCDEFGH12Y']),
        ], collect([$order]));

        $this->assertSame([], $matches);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=BankTransferMatcherTest`

Expected: FAIL class `BankTransferMatcher` not found.

- [ ] **Step 3: Write minimal implementation**

```php
<?php

namespace App\Services\Bank;

use App\Models\ServiceOrder;

class BankTransferMatcher
{
    public const CODE_PATTERN = '/\bORDFB[A-Z0-9]{10}\b/i';

    /**
     * @param  array<int, array<string, mixed>>  $transactions
     * @param  iterable<ServiceOrder>  $pendingOrders
     * @return array<int, array{order: ServiceOrder, txId: string, amount: int, description: string}>
     */
    public function match(array $transactions, iterable $pendingOrders): array
    {
        $byCode = [];
        foreach ($pendingOrders as $order) {
            $byCode[strtoupper($order->order_code)] = $order;
        }

        $matches = [];
        $usedTxn = [];

        foreach ($transactions as $tx) {
            if (($tx['creditDebitIndicator'] ?? '') !== 'CRDT') {
                continue;
            }

            $txId = isset($tx['id']) ? (string) $tx['id'] : '';
            if ($txId === '' || isset($usedTxn[$txId])) {
                continue;
            }

            $amount = (int) $tx['amount'];
            $description = $this->normalize((string) ($tx['description'] ?? ''));

            if (!preg_match_all(self::CODE_PATTERN, $description, $found)) {
                continue;
            }

            foreach ($found[0] as $rawCode) {
                $code = strtoupper($rawCode);
                $order = $byCode[$code] ?? null;
                if (!$order || (int) $order->amount !== $amount) {
                    continue;
                }

                $matches[] = [
                    'order' => $order,
                    'txId' => $txId,
                    'amount' => $amount,
                    'description' => $description,
                ];
                $usedTxn[$txId] = true;
                unset($byCode[$code]);
                break;
            }
        }

        return $matches;
    }

    public function normalize(string $description): string
    {
        $normalized = \Normalizer::normalize($description, \Normalizer::FORM_C) ?: $description;

        return trim(preg_replace('/\s+/u', ' ', $normalized) ?? $normalized);
    }
}
```

If `ext-intl` missing in test image, replace `\Normalizer::normalize` with `$description` (Dockerfile production đã ignore intl lúc build; ưu tiên `Normalizer` khi extension có).

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=BankTransferMatcherTest`

Expected: PASS 4 tests.

- [ ] **Step 5: Commit**

```bash
git add tests/Unit/BankTransferMatcherTest.php app/Services/Bank/BankTransferMatcher.php
git commit -m "$(cat <<'EOF'
Add bank transfer matcher for unique ORDFB codes.

EOF
)"
```

---

### Task 2: HistbankClient

**Files:**
- Create: `app/Services/Bank/HistbankClient.php`
- Modify: `config/services.php` — thêm key `histbank`
- Modify: `.env.example`
- Modify: `tests/Feature/PendingBankPollerTest.php` (bắt đầu Http::fake; hoàn tất Task 4)

**Interfaces:**
- Consumes: `config('services.histbank.base_url')`
- Produces: `HistbankClient::transactions(int $days = 1, int $pageSize = 100): array{ok: bool, transactions: array, status: int}`

- [ ] **Step 1: Add config**

Trong `config/services.php` thêm:

```php
'histbank' => [
    'base_url' => env('HISTBANK_URL', 'http://tpbank-serve:3999'),
    'timeout' => (int) env('HISTBANK_TIMEOUT', 15),
],
'payment' => [
    'window_minutes' => (int) env('PAYMENT_WINDOW_MINUTES', 12),
    'initial_delay_seconds' => (int) env('PAYMENT_INITIAL_DELAY_SECONDS', 20),
],
'vietqr' => [
    'bank_id' => env('VIETQR_BANK_ID', 'TPB'),
    'account_no' => env('VIETQR_ACCOUNT_NO'),
    'account_name' => env('VIETQR_ACCOUNT_NAME'),
    'template' => env('VIETQR_TEMPLATE', 'compact2'),
],
```

`.env.example`:

```
HISTBANK_URL=http://tpbank-serve:3999
HISTBANK_TIMEOUT=15
PAYMENT_WINDOW_MINUTES=12
PAYMENT_INITIAL_DELAY_SECONDS=20
VIETQR_BANK_ID=TPB
VIETQR_ACCOUNT_NO=
VIETQR_ACCOUNT_NAME=
```

- [ ] **Step 2: Implement client**

```php
<?php

namespace App\Services\Bank;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HistbankClient
{
    public function transactions(int $days = 1, int $pageSize = 100): array
    {
        $base = rtrim((string) config('services.histbank.base_url'), '/');
        $timeout = (int) config('services.histbank.timeout', 15);

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->get($base.'/transactions', [
                    'days' => $days,
                    'pageSize' => $pageSize,
                    'pageNumber' => 1,
                ]);
        } catch (ConnectionException $e) {
            Log::warning('histbank unreachable', ['error' => $e->getMessage()]);

            return ['ok' => false, 'transactions' => [], 'status' => 0];
        }

        if (!$response->successful()) {
            Log::warning('histbank non-2xx', ['status' => $response->status()]);

            return ['ok' => false, 'transactions' => [], 'status' => $response->status()];
        }

        $json = $response->json();

        return [
            'ok' => true,
            'transactions' => $json['transactions'] ?? [],
            'status' => $response->status(),
        ];
    }
}
```

- [ ] **Step 3: Commit**

```bash
git add config/services.php .env.example app/Services/Bank/HistbankClient.php
git commit -m "$(cat <<'EOF'
Add Histbank HTTP client for internal transaction fetch.

EOF
)"
```

---

### Task 3: confirmBankMatch in OrderService

**Files:**
- Modify: `app/Services/OrderService.php`
- Modify: `tests/Feature/OrderApiTest.php` (thêm test confirm; giữ create order)

**Interfaces:**
- Consumes: `confirmBankMatch(string $orderCode, string $bankTxnId): array` — cùng lock/dedupe như `verifyPayment` nhưng **cho phép paid sau expire** (reconcile).
- Produces: fires `PaymentSuccess` như hiện tại.

- [ ] **Step 1: Write feature test**

Thêm vào `tests/Feature/OrderApiTest.php`:

```php
public function test_confirm_bank_match_marks_paid_once(): void
{
    Event::fake([PaymentSuccess::class]);
    $service = Service::factory()->create(['is_active' => true, 'price' => 100000]);
    $order = ServiceOrder::factory()->create([
        'service_id' => $service->id,
        'amount' => 100000,
        'status' => ServiceOrder::STATUS_PENDING,
        'order_code' => 'ORDFBABCDEFGH12',
        'expires_at' => now()->addMinutes(12),
    ]);

    $first = app(\App\Services\OrderService::class)->confirmBankMatch($order->order_code, 'tx-99');
    $second = app(\App\Services\OrderService::class)->confirmBankMatch($order->order_code, 'tx-99');

    $this->assertTrue($first['success']);
    $this->assertFalse($second['success']);
    $this->assertSame('already_paid', $second['error']);
    Event::assertDispatched(PaymentSuccess::class);
}
```

- [ ] **Step 2: Run to see it fail**

Run: `php artisan test --filter=test_confirm_bank_match_marks_paid_once`

Expected: FAIL method not found.

- [ ] **Step 3: Implement**

Trong `OrderService`, extract phần lock từ `verifyPayment` thành:

```php
public function confirmBankMatch(string $orderCode, string $bankTxnId, bool $allowExpired = false): array
{
    try {
        return DB::transaction(function () use ($orderCode, $bankTxnId, $allowExpired) {
            $order = $this->findOrderByCodeWithLock($orderCode);

            if (!$order) {
                return ['success' => false, 'error' => 'order_not_found', 'message' => 'Không tìm thấy đơn hàng.'];
            }

            if ($order->status === ServiceOrder::STATUS_PAID) {
                return ['success' => false, 'error' => 'already_paid', 'message' => 'Đơn hàng đã được thanh toán.'];
            }

            if (!$allowExpired && $order->isTimeExpired()) {
                $this->expireOrderIfNeeded($order);

                return ['success' => false, 'error' => 'order_expired', 'message' => 'Đơn hàng đã hết hạn.'];
            }

            $existing = $this->repository->findByBankTxnIdForUpdate($bankTxnId);
            if ($existing) {
                return ['success' => false, 'error' => 'duplicate_transaction', 'message' => 'Giao dịch này đã được xử lý.'];
            }

            $order->update([
                'status' => ServiceOrder::STATUS_PAID,
                'paid_at' => now(),
                'bank_txn_id' => $bankTxnId,
            ]);

            event(new PaymentSuccess($order));
            $this->webPushTestService->notifyPaid($order);

            return [
                'success' => true,
                'data' => [
                    'order_code' => $order->order_code,
                    'status' => $order->status,
                    'paid_at' => $order->paid_at->toIso8601String(),
                ],
            ];
        });
    } catch (\Exception $e) {
        Log::error('Order confirmBankMatch failed', [
            'order_code' => $orderCode,
            'error' => $e->getMessage(),
        ]);

        return ['success' => false, 'error' => 'transaction_failed', 'message' => 'Có lỗi xảy ra khi xác nhận thanh toán.'];
    }
}

public function verifyPayment(string $orderCode, string $bankTxnId): array
{
    return $this->confirmBankMatch($orderCode, $bankTxnId, false);
}
```

- [ ] **Step 4: Run tests**

Run: `php artisan test --filter=OrderApiTest`

Expected: PASS, gồm test mới.

- [ ] **Step 5: Commit**

```bash
git add app/Services/OrderService.php tests/Feature/OrderApiTest.php
git commit -m "$(cat <<'EOF'
Add confirmBankMatch with txn dedupe for histbank matches.

EOF
)"
```

---

### Task 4: PendingBankPoller + artisan command + schedule

**Files:**
- Create: `app/Services/Bank/PendingBankPoller.php`
- Create: `app/Console/Commands/PollPendingBankTransfers.php`
- Create: `tests/Feature/PendingBankPollerTest.php`
- Modify: `bootstrap/app.php`

**Interfaces:**
- Consumes: `ServiceOrder` pending; `HistbankClient`; `BankTransferMatcher`; `OrderService::confirmBankMatch`
- Produces: `PendingBankPoller::run(): array{fetched: bool, matched: int, reason: string}`

- [ ] **Step 1: Failing tests**

```php
<?php

namespace Tests\Feature;

use App\Events\PaymentSuccess;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Services\Bank\PendingBankPoller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PendingBankPollerTest extends TestCase
{
    use RefreshDatabase;

    public function test_idle_does_not_call_histbank(): void
    {
        Http::fake();
        $result = app(PendingBankPoller::class)->run();
        $this->assertFalse($result['fetched']);
        $this->assertSame('idle', $result['reason']);
        Http::assertNothingSent();
    }

    public function test_skips_fetch_during_initial_delay(): void
    {
        Http::fake();
        ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now(),
        ]);

        $result = app(PendingBankPoller::class)->run();
        $this->assertFalse($result['fetched']);
        $this->assertSame('initial_delay', $result['reason']);
        Http::assertNothingSent();
    }

    public function test_matches_and_marks_paid(): void
    {
        Event::fake([PaymentSuccess::class]);
        Cache::flush();
        config()->set('services.histbank.base_url', 'http://histbank.test');

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now()->subMinutes(1),
        ]);

        Http::fake([
            'http://histbank.test/transactions*' => Http::response([
                'count' => 1,
                'transactions' => [[
                    'id' => 'tx-live-1',
                    'description' => 'CK ORDFBABCDEFGH12',
                    'amount' => 100000,
                    'creditDebitIndicator' => 'CRDT',
                ]],
            ], 200),
        ]);

        $result = app(PendingBankPoller::class)->run();

        $this->assertTrue($result['fetched']);
        $this->assertSame(1, $result['matched']);
        $this->assertSame(ServiceOrder::STATUS_PAID, $order->fresh()->status);
        Event::assertDispatched(PaymentSuccess::class);
    }
}
```

- [ ] **Step 2: Run — expect FAIL**

Run: `php artisan test --filter=PendingBankPollerTest`

- [ ] **Step 3: Implement poller**

```php
<?php

namespace App\Services\Bank;

use App\Models\ServiceOrder;
use App\Services\OrderService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PendingBankPoller
{
    public function __construct(
        private HistbankClient $client,
        private BankTransferMatcher $matcher,
        private OrderService $orders,
    ) {}

    public function run(bool $force = false): array
    {
        $pending = ServiceOrder::query()
            ->where('status', ServiceOrder::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->orderBy('created_at')
            ->get();

        if ($pending->isEmpty()) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'idle'];
        }

        $newestAge = $pending->max('created_at')->diffInSeconds(now());
        $oldestAge = $pending->min('created_at')->diffInSeconds(now());
        $initial = (int) config('services.payment.initial_delay_seconds', 20);

        if (!$force && $newestAge < $initial) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'initial_delay'];
        }

        $delay = $this->intervalSeconds($oldestAge);
        $last = Cache::get('histbank:last_poll_at');
        $backoffUntil = Cache::get('histbank:backoff_until');

        if (!$force && $backoffUntil && now()->lt($backoffUntil)) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'backoff'];
        }

        if (!$force && $last && now()->diffInSeconds($last) < $delay) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'throttled'];
        }

        $result = $this->client->transactions(1, 100);
        Cache::put('histbank:last_poll_at', now(), 3600);

        if (!$result['ok']) {
            $this->recordBackoff($result['status']);
            Log::warning('histbank poll failed', ['status' => $result['status']]);

            return ['fetched' => true, 'matched' => 0, 'reason' => 'histbank_error'];
        }

        Cache::forget('histbank:backoff_until');
        Cache::forget('histbank:backoff_step');

        $matches = $this->matcher->match($result['transactions'], $pending);
        $paid = 0;
        foreach ($matches as $match) {
            $confirm = $this->orders->confirmBankMatch($match['order']->order_code, $match['txId']);
            if ($confirm['success']) {
                $paid++;
            }
        }

        return ['fetched' => true, 'matched' => $paid, 'reason' => 'ok'];
    }

    private function intervalSeconds(int $oldestAge): int
    {
        if ($oldestAge < 120) {
            return 25;
        }
        if ($oldestAge < 360) {
            return 40;
        }

        return 60;
    }

    private function recordBackoff(int $status): void
    {
        if (!in_array($status, [401, 423, 429, 0], true)) {
            return;
        }

        $step = (int) Cache::get('histbank:backoff_step', 0);
        $minutes = [2, 5, 15][min($step, 2)];
        Cache::put('histbank:backoff_step', $step + 1, 3600);
        Cache::put('histbank:backoff_until', now()->addMinutes($minutes), 3600);
    }
}
```

Command:

```php
<?php

namespace App\Console\Commands;

use App\Services\Bank\PendingBankPoller;
use Illuminate\Console\Command;

class PollPendingBankTransfers extends Command
{
    protected $signature = 'bank:poll-pending {--force : Ignore delay/throttle}';

    protected $description = 'Fetch histbank once for all pending service orders';

    public function handle(PendingBankPoller $poller): int
    {
        $result = $poller->run((bool) $this->option('force'));
        $this->info(json_encode($result));

        return self::SUCCESS;
    }
}
```

`bootstrap/app.php` — thêm `withSchedule` trước `withMiddleware`:

```php
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('bank:poll-pending')->everyFifteenSeconds()->withoutOverlapping(2);
        $schedule->command('bank:reconcile-overnight')->dailyAt('02:30');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // existing
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

`bank:reconcile-overnight` sẽ 404 cho đến Task 8 — tạm comment dòng reconcile nếu test boot schedule, hoặc tạo stub command rỗng trả SUCCESS.

Stub tạm (xóa logic ở Task 8):

```php
class ReconcileOvernightBankTransfers extends Command
{
    protected $signature = 'bank:reconcile-overnight';
    protected $description = 'Re-check unpaid service orders against histbank';
    public function handle(): int { return self::SUCCESS; }
}
```

- [ ] **Step 4: Run tests**

Run: `php artisan test --filter=PendingBankPollerTest`

Expected: PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Services/Bank/PendingBankPoller.php app/Console/Commands/PollPendingBankTransfers.php app/Console/Commands/ReconcileOvernightBankTransfers.php tests/Feature/PendingBankPollerTest.php bootstrap/app.php
git commit -m "$(cat <<'EOF'
Poll histbank only while service orders are pending.

EOF
)"
```

---

### Task 5: VietQR + 12 phút + email optional

**Files:**
- Create: `app/Services/VietQrService.php`
- Create: `database/migrations/2026_09_15_000001_add_customer_email_to_service_orders_table.php`
- Create: `tests/Feature/VietQrOrderCreateTest.php`
- Modify: `app/Services/OrderService.php` (`expires_at`, `customer_email`, qr fields)
- Modify: `app/Http/Controllers/Api/OrderController.php` (validate email)
- Modify: `app/Models/ServiceOrder.php` fillable
- Modify: `database/factories/ServiceOrderFactory.php`

**Interfaces:**
- Consumes: amount + order_code + vietqr config
- Produces: `qr_image_url`, `transfer_content`, `qr_content` (= transfer_content)

- [ ] **Step 1: Migration + factory**

```php
Schema::table('service_orders', function (Blueprint $table) {
    $table->string('customer_email')->nullable()->after('facebook_profile_link');
});
```

Fillable thêm `customer_email`. Factory `expires_at` dùng `now()->addMinutes(12)`.

- [ ] **Step 2: Failing test**

```php
public function test_create_order_returns_vietqr_and_accepts_optional_email(): void
{
    config()->set('services.vietqr.bank_id', 'TPB');
    config()->set('services.vietqr.account_no', '03738073001');
    config()->set('services.vietqr.account_name', 'TIEM NHA DUY');
    config()->set('services.payment.window_minutes', 12);

    $service = Service::factory()->create(['is_active' => true, 'price' => 100000]);

    $response = $this->postJson('/api/v1/orders', [
        'facebook_profile_link' => 'https://facebook.com/testuser',
        'service_id' => $service->id,
        'email' => 'a@example.com',
    ]);

    $response->assertStatus(201);
    $code = $response->json('data.order_code');
    $this->assertMatchesRegularExpression('/^ORDFB[A-Z0-9]{10}$/', $code);
    $this->assertSame($code, $response->json('data.transfer_content'));
    $this->assertStringContainsString('img.vietqr.io', $response->json('data.qr_image_url'));
    $this->assertStringContainsString($code, $response->json('data.qr_image_url'));
    $this->assertDatabaseHas('service_orders', [
        'order_code' => $code,
        'customer_email' => 'a@example.com',
    ]);
}
```

- [ ] **Step 3: VietQrService + OrderService**

```php
<?php

namespace App\Services;

class VietQrService
{
    public function imageUrl(string $orderCode, int $amount): string
    {
        $bank = config('services.vietqr.bank_id');
        $account = config('services.vietqr.account_no');
        $name = config('services.vietqr.account_name');
        $template = config('services.vietqr.template', 'compact2');

        $query = http_build_query([
            'amount' => $amount,
            'addInfo' => $orderCode,
            'accountName' => $name,
        ]);

        return "https://img.vietqr.io/image/{$bank}-{$account}-{$template}.png?{$query}";
    }
}
```

Trong `createOrder`:

```php
'expires_at' => now()->addMinutes((int) config('services.payment.window_minutes', 12)),
'customer_email' => $request->input('email'),
```

và data trả về:

```php
'qr_content' => $order->order_code,
'transfer_content' => $order->order_code,
'qr_image_url' => app(VietQrService::class)->imageUrl($order->order_code, (int) $order->amount),
```

Controller store validation thêm:

```php
'email' => ['nullable', 'email', 'max:255'],
```

- [ ] **Step 4: Run**

Run: `php artisan test --filter=test_create_order_returns_vietqr`

Expected: PASS. Cũng chạy `test_can_create_local_order` — vẫn 201.

- [ ] **Step 5: Commit**

```bash
git add database/migrations/2026_09_15_000001_add_customer_email_to_service_orders_table.php app/Services/VietQrService.php app/Services/OrderService.php app/Http/Controllers/Api/OrderController.php app/Models/ServiceOrder.php database/factories/ServiceOrderFactory.php tests/Feature/VietQrOrderCreateTest.php
git commit -m "$(cat <<'EOF'
Emit VietQR with unique transfer content and optional email.

EOF
)"
```

---

### Task 6: Mail on PaymentSuccess

**Files:**
- Create: `app/Mail/ServiceOrderPaidMail.php`
- Create: `resources/views/mail/service-order-paid.blade.php`
- Create: `app/Listeners/SendServiceOrderPaidMail.php`
- Modify: `app/Providers/EventServiceProvider.php`
- Modify: `tests/Feature/PendingBankPollerTest.php` hoặc `OrderApiTest` — Mail::fake

**Interfaces:**
- Consumes: `PaymentSuccess`
- Produces: mail nếu `customer_email` non-null

- [ ] **Step 1: Test**

```php
public function test_paid_order_sends_mail_when_email_present(): void
{
    Mail::fake();
    $order = ServiceOrder::factory()->create([
        'service_id' => Service::factory(),
        'status' => ServiceOrder::STATUS_PENDING,
        'order_code' => 'ORDFBABCDEFGH12',
        'amount' => 100000,
        'customer_email' => 'a@example.com',
        'expires_at' => now()->addMinutes(12),
    ]);

    app(\App\Services\OrderService::class)->confirmBankMatch($order->order_code, 'tx-mail');

    Mail::assertSent(\App\Mail\ServiceOrderPaidMail::class, function ($mail) {
        return $mail->hasTo('a@example.com');
    });
}

public function test_paid_order_skips_mail_without_email(): void
{
    Mail::fake();
    $order = ServiceOrder::factory()->create([
        'service_id' => Service::factory(),
        'status' => ServiceOrder::STATUS_PENDING,
        'order_code' => 'ORDFBZZZZZZZZZZ',
        'expires_at' => now()->addMinutes(12),
        'customer_email' => null,
    ]);

    app(\App\Services\OrderService::class)->confirmBankMatch($order->order_code, 'tx-nomail');
    Mail::assertNothingSent();
}
```

- [ ] **Step 2: Implement mailable + listener**

Listener:

```php
public function handle(PaymentSuccess $event): void
{
    $email = $event->order->customer_email;
    if (!$email) {
        return;
    }

    Mail::to($email)->send(new ServiceOrderPaidMail($event->order));
}
```

Đăng ký `$listen` trong `EventServiceProvider`.

View ngắn: mã đơn, số tiền, “Thanh toán thành công”.

- [ ] **Step 3: Run tests + commit**

```bash
git commit -m "$(cat <<'EOF'
Email the customer after a matched bank transfer.

EOF
)"
```

---

### Task 7: Lock public verify-payment + disable markPaidTest in production

**Files:**
- Modify: `app/Http/Controllers/Api/OrderController.php`
- Modify: `routes/api.php` nếu cần middleware
- Modify: tests gọi `verify-payment`

**Interfaces:**
- `verify-payment` yêu cầu header `X-Internal-Token` = `config('services.histbank.internal_token')` hoặc 404/401.
- `markPaidTest` return 404 khi `app()->isProduction()`.

- [ ] **Step 1: Tests**

```php
public function test_verify_payment_rejected_without_token(): void
{
    $this->postJson('/api/v1/orders/verify-payment', [
        'order_code' => 'ORDFBABCDEFGH12',
        'bank_txn_id' => 'x',
    ])->assertStatus(401);
}
```

- [ ] **Step 2: Implement** — so sánh `hash_equals` token. Poller không dùng HTTP này.

- [ ] **Step 3: Commit**

```bash
git commit -m "$(cat <<'EOF'
Keep bank confirmation internal to the histbank poller.

EOF
)"
```

---

### Task 8: Reverb container, histbank serve, nightly reconcile

**Files:**
- Modify: `deploy/docker-compose.yml`
- Modify: `.env` trên VPS (không commit secret)
- Modify: `app/Console/Commands/ReconcileOvernightBankTransfers.php`
- Create: `tests/Feature/ReconcileOvernightBankTransfersTest.php`

**Interfaces:**
- Reconcile: lấy đơn `pending` hoặc `expired` trong 24h chưa paid, `force` fetch histbank, `confirmBankMatch(..., allowExpired: true)`.

- [ ] **Step 1: Reconcile test** — order expired 10 phút, fake tx khớp → paid.

```php
$order = ServiceOrder::factory()->create([
    'status' => ServiceOrder::STATUS_EXPIRED,
    'order_code' => 'ORDFBABCDEFGH12',
    'amount' => 100000,
    'expires_at' => now()->subMinutes(10),
    'paid_at' => null,
    'created_at' => now()->subMinutes(20),
]);
```

Nếu `markExpired` đổi status, factory `expired()` đang để `STATUS_PENDING` + expires past — reconcile query:

```php
ServiceOrder::query()
    ->whereNull('paid_at')
    ->where('created_at', '>=', now()->subDay())
    ->whereIn('status', [ServiceOrder::STATUS_PENDING, ServiceOrder::STATUS_EXPIRED])
    ->get();
```

- [ ] **Step 2: Implement command dùng HistbankClient + matcher + confirmBankMatch(..., true)**

- [ ] **Step 3: docker-compose reverb** (cùng Dockerfile app):

```yaml
  reverb:
    build:
      context: ..
      dockerfile: deploy/Dockerfile
    env_file:
      - path: ../.env
        required: true
    environment:
      RUN_MIGRATIONS: "0"
    command: ["php", "artisan", "reverb:start", "--host=0.0.0.0", "--port=8080"]
    ports:
      - "127.0.0.1:9091:8080"
    volumes:
      - ../.env:/var/www/html/.env:ro
      - laravel-storage:/var/www/html/storage
      - laravel-cache:/var/www/html/bootstrap/cache
    depends_on:
      mysql:
        condition: service_healthy
    restart: unless-stopped
    networks: [default, vps-internal]

  app:
    # existing +
    extra_hosts:
      - "host.docker.internal:host-gateway"
    networks: [default, vps-internal]
```

Network:

```yaml
networks:
  vps-internal:
    name: vps-internal
    external: true
```

VPS (không commit):

```bash
docker network create vps-internal || true
cd ~/histbank
# docker-compose.yml tpbank-serve: networks: [vps-internal], không publish public
docker compose --profile serve up -d --build
cd ~/tiemnhaduy/deploy
# .env:
# BROADCAST_CONNECTION=reverb
# REVERB_APP_ID=tiemnhaduy
# REVERB_APP_KEY=...
# REVERB_APP_SECRET=...
# REVERB_HOST=ws.tiemnhaduy.com
# REVERB_PORT=443
# REVERB_SCHEME=https
# REVERB_SERVER_HOST=0.0.0.0
# REVERB_SERVER_PORT=8080
# HISTBANK_URL=http://tpbank-serve:3999
# VIETQR_* bank thật
docker compose up -d --build
```

Cloudflare Zero Trust tunnel **homelab** — Published route:

- Hostname `ws`, domain `tiemnhaduy.com`
- Type HTTP → `127.0.0.1:9091`
- originRequest: connectTimeout 30s, keepAliveTimeout 90s, disableChunkedEncoding true (copy `ws.onthilaixe.online`)

Nếu DNS `ws` đã tồn tại, xóa record rồi Add như lần `www`.

- [ ] **Step 4: Verify**

```bash
php artisan test
docker compose -f ~/tiemnhaduy/deploy/docker-compose.yml ps
curl -sS http://127.0.0.1:3999/health   # từ host; từ app: wget tpbank-serve:3999/health
curl -sS http://127.0.0.1:9091  # reverb
```

Expected: PHPUnit green; histbank health ok; reverb process up.

- [ ] **Step 5: Commit compose + reconcile only (không .env)**

```bash
git add deploy/docker-compose.yml app/Console/Commands/ReconcileOvernightBankTransfers.php tests/Feature/ReconcileOvernightBankTransfersTest.php .env.example
git commit -m "$(cat <<'EOF'
Run Reverb beside the app and reconcile late bank transfers.

EOF
)"
```

---

## Ghi chú triển khai VPS

1. `docker network create vps-internal`
2. Histbank serve trên network đó, không watcher 10s.
3. Laravel `.env` `HISTBANK_URL=http://tpbank-serve:3999`
4. Rebuild `tiemnhaduy` + service `reverb`
5. Public hostname `ws.tiemnhaduy.com` trên tunnel `homelab`
6. SPA: Echo host `ws.tiemnhaduy.com`, channel `private-order.{order_code}`, event `.payment.success`; fallback GET order 3s

## Self-review

- Spec: matcher, idle poll, VietQR, Reverb, mail, reconcile, lock verify-payment — đều có task.
- Không TBD. Tên method thống nhất `confirmBankMatch`, `PendingBankPoller::run`, `bank:poll-pending`.
- Prefix mã `ORDFB` khớp `OrderService::createOrder` hiện tại (không dùng `ORD-` của `generateOrderCode()` trừ khi đổi createOrder cho thống nhất — **giữ ORDFB**).
