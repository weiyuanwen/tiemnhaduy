<?php

namespace App\Jobs;

use App\Models\ServiceOrder;
use App\Services\FacebookProfileLookup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class LookupFacebookProfileJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(public int $orderId) {}

    public function handle(FacebookProfileLookup $lookup): void
    {
        $order = ServiceOrder::query()->find($this->orderId);
        if (! $order || ! $order->facebook_profile_link) {
            return;
        }

        $facebook = $lookup->resolve($order->facebook_profile_link);
        $name = $facebook['name'] ?: $order->facebook_name;
        $id = $facebook['id'] ?: $order->facebook_id;

        if ($name === $order->facebook_name && $id === $order->facebook_id) {
            return;
        }

        $order->forceFill([
            'facebook_name' => $name,
            'facebook_id' => $id,
        ])->save();

        Log::info('Facebook profile lookup completed', [
            'order_code' => $order->order_code,
            'facebook_id' => $id,
        ]);
    }
}
