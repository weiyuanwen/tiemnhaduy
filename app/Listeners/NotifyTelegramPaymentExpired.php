<?php

namespace App\Listeners;

use App\Events\PaymentExpired;
use App\Services\TelegramNotifier;

class NotifyTelegramPaymentExpired
{
    public function __construct(private TelegramNotifier $telegram) {}

    public function handle(PaymentExpired $event): void
    {
        $order = $event->order;
        $this->telegram->notify(implode("\n", [
            'Tiệm Nhà Duy: đơn hết hạn / chưa CK',
            'Mã: '.$order->order_code,
            'Số tiền: '.number_format((int) $order->amount).' VND',
        ]));
    }
}
