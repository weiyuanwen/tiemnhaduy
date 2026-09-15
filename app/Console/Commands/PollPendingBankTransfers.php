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
        $this->info((string) json_encode($result));

        return self::SUCCESS;
    }
}
