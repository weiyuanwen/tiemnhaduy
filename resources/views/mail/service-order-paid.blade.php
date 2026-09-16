<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanh toán thành công</title>
</head>
<body style="margin:0;padding:0;background:#111111;font-family:Georgia, 'Times New Roman', serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#111111;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#FBFAF3;color:#111111;">
                    <tr>
                        <td style="padding:28px 28px 12px;border-bottom:1px solid #EAE4D5;">
                            <p style="margin:0 0 6px;font-size:12px;letter-spacing:0.16em;text-transform:uppercase;color:#C9A581;">Tiệm Nhà Duy</p>
                            <h1 style="margin:0;font-size:28px;line-height:1.2;font-weight:400;">Thanh toán thành công</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 28px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.6;">
                                Đã nhận phí nhóm Facebook <strong>Ăn vặt Chư Sê</strong>. Hệ thống đang tắt phê duyệt bài viết cho tài khoản của bạn.
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F0EFE5;margin:0 0 20px;">
                                <tr>
                                    <td style="padding:16px 18px;font-size:15px;line-height:1.7;">
                                        Mã đơn: <strong>{{ $order->order_code }}</strong><br>
                                        Số tiền: <strong>{{ number_format((int) $order->amount, 0, ',', '.') }} VND</strong><br>
                                        Facebook: <strong>{{ $order->facebook_name ?: 'Chưa có tên' }}</strong><br>
                                        ID: {{ $order->facebook_id ?: '—' }}<br>
                                        Link: {{ $order->facebook_profile_link ?: '—' }}
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 18px;font-size:15px;line-height:1.6;">
                                Bài viết đang chờ duyệt sẽ được mở sau khi hệ thống xác nhận chuyển khoản. Không cần gửi thêm ảnh bill.
                            </p>
                            <a href="{{ url('/thanh-toan') }}" style="display:inline-block;background:#111111;color:#FBFAF3;text-decoration:none;padding:12px 20px;font-size:13px;letter-spacing:0.08em;text-transform:uppercase;">
                                Xem trang thanh toán
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 28px;font-size:13px;line-height:1.6;color:#555555;">
                            Nếu bạn không thực hiện giao dịch này, hãy trả lời email này hoặc gọi +84 981 314 516.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
