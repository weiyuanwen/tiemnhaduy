<?php

namespace App\Console\Commands;

use App\Models\ServiceOrder;
use App\Services\Bank\BankTransferMatcher;
use App\Services\Bank\HistbankClient;
use App\Services\OrderService;
use Illuminate\Console\Command;

class ReconcileOvernightBankTransfers extends Command
{
    protected $signature = 'bank:reconcile-overnight';

    protected $description = 'Re-check unpaid service orders against histbank';

    public function handle(HistbankClient $client, BankTransferMatcher $matcher, OrderService $orders): int
    {
        $unpaid = ServiceOrder::query()
            ->whereNull('paid_at')
            ->where('created_at', '>=', now()->subDay())
            ->whereIn('status', [ServiceOrder::STATUS_PENDING, ServiceOrder::STATUS_EXPIRED])
            ->get();

        if ($unpaid->isEmpty()) {
            $this->info((string) json_encode(['fetched' => false, 'matched' => 0, 'reason' => 'idle']));

            return self::SUCCESS;
        }

        $result = $client->transactions(1, 100);
        if (! $result['ok']) {
            $this->error((string) json_encode(['fetched' => true, 'matched' => 0, 'reason' => 'histbank_error']));

            return self::FAILURE;
        }

        $matches = $matcher->match($result['transactions'], $unpaid);
        $paid = 0;
        foreach ($matches as $match) {
            $confirm = $orders->confirmBankMatch($match['order']->order_code, $match['txId'], true);
            if ($confirm['success']) {
                $paid++;
            }
        }

        $this->info((string) json_encode(['fetched' => true, 'matched' => $paid, 'reason' => 'ok']));

        return self::SUCCESS;
    }
}
