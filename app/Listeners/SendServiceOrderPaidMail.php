<?php

namespace App\Listeners;

use App\Events\PaymentSuccess;
use App\Mail\ServiceOrderPaidMail;
use Illuminate\Support\Facades\Mail;

class SendServiceOrderPaidMail
{
    public function handle(PaymentSuccess $event): void
    {
        $email = $event->order->customer_email;
        if (! $email) {
            return;
        }

        Mail::to($email)->send(new ServiceOrderPaidMail($event->order));
    }
}
