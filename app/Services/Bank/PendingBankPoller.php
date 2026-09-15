<?php

namespace App\Services\Bank;

use App\Models\ServiceOrder;
use App\Services\OrderService;
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
        $pending = ServiceOrder::query()
            ->where('status', ServiceOrder::STATUS_PENDING)
            ->where('expires_at', '>', now())
            ->orderBy('created_at')
            ->get();

        if ($pending->isEmpty()) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'idle'];
        }

        $newestAge = (int) Carbon::parse($pending->max('created_at'))->diffInSeconds(now());
        $oldestAge = (int) Carbon::parse($pending->min('created_at'))->diffInSeconds(now());
        $initial = (int) config('services.payment.initial_delay_seconds', 20);

        if (! $force && $newestAge < $initial) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'initial_delay'];
        }

        $delay = $this->intervalSeconds($oldestAge);
        $last = Cache::get('histbank:last_poll_at');
        $backoffUntil = Cache::get('histbank:backoff_until');

        if (! $force && $backoffUntil && now()->lt(Carbon::parse($backoffUntil))) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'backoff'];
        }

        if (! $force && $last && now()->diffInSeconds(Carbon::parse($last)) < $delay) {
            return ['fetched' => false, 'matched' => 0, 'reason' => 'throttled'];
        }

        $result = $this->client->transactions(1, 100);
        Cache::put('histbank:last_poll_at', now()->toIso8601String(), 3600);

        if (! $result['ok']) {
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
        if (! in_array($status, [401, 423, 429, 0], true)) {
            return;
        }

        $step = (int) Cache::get('histbank:backoff_step', 0);
        $minutes = [2, 5, 15][min($step, 2)];
        Cache::put('histbank:backoff_step', $step + 1, 3600);
        Cache::put('histbank:backoff_until', now()->addMinutes($minutes)->toIso8601String(), 3600);
    }
}
