<?php

namespace App\Services\Bank;

use App\Models\ServiceOrder;
use App\Services\OrderService;
use App\Services\TelegramNotifier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PendingBankPoller
{
    public function __construct(
        private HistbankClient $client,
        private BankTransferMatcher $matcher,
        private OrderService $orders,
    ) {}

    /**
     * @return array{fetched: bool, matched: int, reason: string}
     */
    public function run(bool $force = false): array
    {
        $unpaid = ServiceOrder::query()
            ->whereNull('paid_at')
            ->whereIn('status', [ServiceOrder::STATUS_PENDING, ServiceOrder::STATUS_EXPIRED])
            ->where('created_at', '>=', now()->subHours(6))
            ->orderBy('created_at')
            ->get();

        if ($unpaid->isEmpty()) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'idle'];
        }

        $pendingCount = $unpaid->where('status', ServiceOrder::STATUS_PENDING)->count();
        $expiredCount = $unpaid->where('status', ServiceOrder::STATUS_EXPIRED)->count();
        $oldestAge = (int) Carbon::parse($unpaid->min('created_at'))->diffInSeconds(now(), true);
        $initial = (int) config('services.payment.initial_delay_seconds', 20);

        if (! $force && $oldestAge < $initial) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'initial_delay'];
        }

        $delay = $this->intervalSeconds($oldestAge);
        $last = Cache::get('histbank:last_poll_at');
        $backoffUntil = Cache::get('histbank:backoff_until');

        if (! $force && $backoffUntil && now()->lt(Carbon::parse($backoffUntil))) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'backoff'];
        }

        if (! $force && $last && Carbon::parse($last)->diffInSeconds(now(), true) < $delay) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'throttled'];
        }

        $result = $this->client->transactions(1, 400);
        Cache::put('histbank:last_poll_at', now()->toIso8601String(), 3600);

        if (! $result['ok']) {
            $this->recordBackoff($result['status']);
            Log::warning('histbank poll failed', ['status' => $result['status']]);
            $this->notifyHistbankError($result['status'], $pendingCount, $expiredCount);

            return ['fetched' => true, 'matched' => 0, 'reason' => 'histbank_error'];
        }

        Cache::forget('histbank:backoff_until');
        Cache::forget('histbank:backoff_step');

        $matches = $this->matcher->match($result['transactions'], $unpaid);
        $paid = 0;
        foreach ($matches as $match) {
            $confirm = $this->orders->confirmBankMatch(
                $match['order']->order_code,
                $match['txId'],
                true,
                [
                    'amount' => $match['amount'],
                    'description' => $match['description'],
                ]
            );
            if ($confirm['success']) {
                $paid++;
            }
        }

        if ($paid === 0) {
            $credits = collect($result['transactions'])
                ->filter(fn ($tx) => ($tx['creditDebitIndicator'] ?? '') === 'CRDT');
            Log::info('histbank poll unmatched', [
                'pending' => $pendingCount,
                'expired' => $expiredCount,
                'fetched' => count($result['transactions']),
                'credits' => $credits->count(),
                'credits_with_code' => $credits->filter(
                    fn ($tx) => (bool) preg_match(BankTransferMatcher::CODE_PATTERN, (string) ($tx['description'] ?? ''))
                )->count(),
            ]);
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
        if (! in_array($status, [0, 401, 423, 429], true) && $status < 500) {
            return;
        }

        $step = (int) Cache::get('histbank:backoff_step', 0);
        $minutes = [2, 5, 15][min($step, 2)];
        Cache::put('histbank:backoff_step', $step + 1, 3600);
        Cache::put('histbank:backoff_until', now()->addMinutes($minutes)->toIso8601String(), 3600);
    }

    private function notifyHistbankError(int $status, int $pendingCount, int $expiredCount): void
    {
        if (! Cache::add('telegram:histbank_error', 1, 600)) {
            return;
        }

        app(TelegramNotifier::class)->notify(
            $this->histbankErrorReport($status, $pendingCount, $expiredCount)
        );
    }

    public function histbankErrorReport(int $status, int $pendingCount, int $expiredCount): string
    {
        $when = now('Asia/Ho_Chi_Minh')->format('d/m/Y H:i').' (GMT+7)';
        $code = $status === 0 ? 'HTTP 0' : 'HTTP '.$status;
        $meaning = match (true) {
            $status === 0 => 'Không kết nối được histbank (timeout hoặc mạng nội bộ).',
            $status >= 500 => 'Histbank nhận request nhưng trả lỗi server, chưa lấy được sao kê.',
            in_array($status, [401, 423], true) => 'Histbank từ chối truy cập sao kê.',
            $status === 429 => 'Histbank đang giới hạn tần suất gọi.',
            default => 'Histbank trả mã lỗi không thành công.',
        };
        $waiting = $pendingCount + $expiredCount;

        return implode("\n", [
            'Tiệm Nhà Duy — báo cáo đối soát CK',
            '',
            'Thời điểm: '.$when,
            'Kết quả: không lấy được sao kê',
            'Mã lỗi: '.$code,
            $meaning,
            'Đơn chưa khớp: '.$waiting.' (đang chờ '.$pendingCount.', hết hạn chưa paid '.$expiredCount.')',
            '',
            'Đã xử lý:',
            '• Thử lại 1 lần, rồi tạm giãn chu kỳ gọi histbank',
            '• Khi histbank sống lại sẽ tự khớp CK, gồm đơn hết hạn trong 6 giờ',
            '• Đối soát đêm 02:30 vẫn chạy như lớp dự phòng',
            '',
            'Khách không bị trừ thêm. QR vẫn tạo bình thường; chỉ tạm chưa tự xác nhận CK.',
        ]);
    }
}
