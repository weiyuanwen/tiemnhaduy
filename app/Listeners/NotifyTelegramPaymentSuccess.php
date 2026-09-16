<?php

namespace App\Listeners;

use App\Events\PaymentSuccess;
use App\Mail\ServiceOrderPaidMail;
use App\Services\TelegramNotifier;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyTelegramPaymentSuccess
{
    public function __construct(private TelegramNotifier $telegram) {}

    public function handle(PaymentSuccess $event): void
    {
        $order = $event->order;
        $email = $order->customer_email;
        $mailLine = 'Email: không có';

        if ($email) {
            try {
                Mail::to($email)->send(new ServiceOrderPaidMail($order));
                $mailLine = 'Email: đã gửi '.$email;
            } catch (\Throwable $e) {
                $mailLine = 'Email: gửi lỗi '.$email;
                Log::warning('Paid-order mail failed', [
                    'order_code' => $order->order_code,
                    'error' => $e->getMessage(),
                ]);
                $this->telegram->notify(implode("\n", [
                    'Tiệm Nhà Duy: gửi mail thất bại',
                    'Mã: '.$order->order_code,
                    'Lỗi: '.$e->getMessage(),
                ]));
            }
        }

        $this->telegram->notify(implode("\n", array_merge([
            'Tiệm Nhà Duy: thanh toán thành công',
            'Mã: '.$order->order_code,
            'Số tiền: '.number_format((int) $order->amount).' VND',
        ], $order->facebookTelegramLines(), [
            'Đang tắt phê duyệt bài viết trên Facebook...',
            $mailLine,
        ])));
    }
}
