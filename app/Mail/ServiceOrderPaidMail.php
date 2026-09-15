<?php

namespace App\Mail;

use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceOrderPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ServiceOrder $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address'),
                (string) config('mail.from.name')
            ),
            subject: 'Thanh toán thành công — '.$this->order->order_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.service-order-paid',
        );
    }
}
