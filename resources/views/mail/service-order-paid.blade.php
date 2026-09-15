<x-mail::message>
# Thanh toán thành công

Đơn **{{ $order->order_code }}** đã được ghi nhận.

Số tiền: **{{ number_format($order->amount) }} VND**

Cảm ơn bạn đã thanh toán.

{{ config('app.name') }}
</x-mail::message>
