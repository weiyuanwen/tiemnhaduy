<?php

namespace App\Jobs;

use App\Models\ServiceOrder;
use App\Services\FacebookGroupApproverClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DisableFacebookPostApprovalJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 120;

    public function __construct(public int $orderId) {}

    public function handle(FacebookGroupApproverClient $approver): void
    {
        $order = ServiceOrder::query()->find($this->orderId);
        if (! $order || ! $order->facebook_profile_link) {
            return;
        }

        $result = $approver->disablePostApproval(
            $order->facebook_profile_link,
            $order->facebook_id,
            $order->facebook_name,
        );

        $order->forceFill([
            'facebook_approval_result' => $result,
            'facebook_approval_disabled_at' => ($result['ok'] ?? false) && empty($result['skipped'])
                ? now()
                : $order->facebook_approval_disabled_at,
        ])->save();

        Log::info('Facebook post-approval disable attempted', [
            'order_code' => $order->order_code,
            'result' => $result,
        ]);
    }
}
