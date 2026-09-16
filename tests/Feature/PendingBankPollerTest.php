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

    public function test_fetches_again_after_throttle_window(): void
    {
        Cache::flush();
        config()->set('services.histbank.base_url', 'http://histbank.test');
        Cache::put('histbank:last_poll_at', now()->subSeconds(30)->toIso8601String());

        ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now()->subMinutes(1),
        ]);

        Http::fake([
            'http://histbank.test/transactions*' => Http::response([
                'count' => 0,
                'transactions' => [],
            ], 200),
        ]);

        $result = app(PendingBankPoller::class)->run();
        $this->assertTrue($result['fetched']);
        $this->assertSame('ok', $result['reason']);
        Http::assertSent(fn ($request) => str_contains($request->url(), '/transactions'));
    }

    public function test_throttles_when_last_poll_is_recent(): void
    {
        Cache::flush();
        config()->set('services.histbank.base_url', 'http://histbank.test');
        Cache::put('histbank:last_poll_at', now()->subSeconds(5)->toIso8601String());

        ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now()->subMinutes(1),
        ]);

        Http::fake();
        $result = app(PendingBankPoller::class)->run();
        $this->assertFalse($result['fetched']);
        $this->assertSame('throttled', $result['reason']);
        Http::assertNothingSent();
    }

    public function test_matches_pending_order_after_expiry_window(): void
    {
        Event::fake([PaymentSuccess::class]);
        Cache::flush();
        config()->set('services.histbank.base_url', 'http://histbank.test');

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->subMinutes(5),
            'created_at' => now()->subMinutes(20),
        ]);

        Http::fake([
            'http://histbank.test/transactions*' => Http::response([
                'count' => 1,
                'transactions' => [[
                    'id' => 'tx-late-2',
                    'description' => 'MBVCB.1.ABC.ORDFBABCDEFGH12.CT',
                    'amount' => '100000',
                    'creditDebitIndicator' => 'CRDT',
                ]],
            ], 200),
        ]);

        $result = app(PendingBankPoller::class)->run();
        $this->assertTrue($result['fetched']);
        $this->assertSame(1, $result['matched']);
        $this->assertSame(ServiceOrder::STATUS_PAID, $order->fresh()->status);
    }

    public function test_reads_transaction_infos_payload_from_histbank(): void
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
                'transactionInfos' => [[
                    'id' => 'tx-infos-1',
                    'description' => 'IBFTORDFBABCDEFGH12',
                    'amount' => '100000',
                    'creditDebitIndicator' => 'CRDT',
                ]],
            ], 200),
        ]);

        $result = app(PendingBankPoller::class)->run();

        $this->assertTrue($result['fetched']);
        $this->assertSame(1, $result['matched']);
        $this->assertSame(ServiceOrder::STATUS_PAID, $order->fresh()->status);
    }
}
