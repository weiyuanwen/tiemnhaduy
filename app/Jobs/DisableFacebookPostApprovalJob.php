<?php

namespace App\Jobs;

use App\Models\ServiceOrder;
use App\Services\FacebookGroupApproverClient;
use App\Services\TelegramNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DisableFacebookPostApprovalJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(public int $orderId) {}

    public function handle(FacebookGroupApproverClient $approver, TelegramNotifier $telegram): void
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

        $ok = ($result['ok'] ?? false) && empty($result['skipped']);
        $order->forceFill([
            'facebook_approval_result' => $result,
            'facebook_approval_disabled_at' => $ok ? now() : $order->facebook_approval_disabled_at,
        ])->save();

        Log::info('Facebook post-approval disable attempted', [
            'order_code' => $order->order_code,
            'result' => $result,
        ]);

        $reason = (string) ($result['reason'] ?? 'unknown');
        $telegram->notify(implode("\n", array_merge([
            $ok
                ? 'Tiệm Nhà Duy: đã tắt phê duyệt bài viết trên Facebook'
                : 'Tiệm Nhà Duy: chưa tắt được phê duyệt bài viết',
            'Mã: '.$order->order_code,
        ], $order->facebookTelegramLines(), [
            $ok ? 'Kết quả: '.$reason : 'Lý do: '.$reason,
        ])));
    }
}
