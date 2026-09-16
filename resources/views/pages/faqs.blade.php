@extends('layouts.rosta')

@section('title', 'Câu hỏi thường gặp | Tiệm Nhà Duy')
@section('meta_description', 'Giải đáp đặt hàng, giao hàng, bảo quản cà phê Robusta, mắc ca và nông sản Tây Nguyên tại Tiệm Nhà Duy.')
@section('og_title', 'Câu hỏi thường gặp | Tiệm Nhà Duy')
@section('og_description', 'Hỏi đáp về đặt hàng, vận chuyển, bảo quản cà phê và nông sản từ Chư Sê, Gia Lai.')
@section('og_image', asset('rosta/images/faq-image.jpg'))
@section('og_image_alt', 'Câu hỏi thường gặp về nông sản Tiệm Nhà Duy')
@section('canonical_url', route('faqs'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "name": "Câu hỏi thường gặp",
    "description": "Giải đáp đặt hàng, giao hàng, bảo quản cà phê Robusta, mắc ca và nông sản Tây Nguyên tại Tiệm Nhà Duy.",
    "url": "{{ route('faqs') }}",
    "inLanguage": "vi-VN",
    "mainEntity": [
        {
            "@@type": "Question",
            "name": "Làm sao để đặt hàng nông sản?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Gửi thư tại trang Liên hệ hoặc nhắn Tiệm Nhà Duy qua điện thoại +84 981 314 516. Ghi rõ sản phẩm, khối lượng và địa chỉ nhận hàng."
            }
        },
        {
            "@@type": "Question",
            "name": "Tiệm giao hàng những tỉnh nào?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Tiệm Nhà Duy giao toàn quốc. Thời gian nhận hàng tùy đơn vị vận chuyển và khu vực, thường 1–4 ngày làm việc."
            }
        },
        {
            "@@type": "Question",
            "name": "Cà phê Robusta Gia Lai bảo quản thế nào?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Giữ hạt trong túi zip hoặc hũ kín, nơi khô ráo, tránh nắng. Nên dùng trong 4–8 tuần sau khi mở để giữ hương."
            }
        },
        {
            "@@type": "Question",
            "name": "Có bán sỉ cho quán cà phê không?",
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": "Có. Tiệm cung cấp cà phê, mắc ca và tiêu đen theo sản lượng. Liên hệ để nhận báo giá sỉ theo mùa vụ."
            }
        }
    ]
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Trang chủ",
            "item": "{{ route('home') }}"
        },
        {
            "@@type": "ListItem",
            "position": 2,
            "name": "Câu hỏi thường gặp",
            "item": "{{ route('faqs') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    <style>
        #MainContent {padding-top: 110px;}
        .container.text-only {max-width: 800px; color: #000; padding-bottom: 90px;}
        .text-only h1 {font-size: clamp(36px, 6vw, 64px); margin-bottom: 20px;}
        .a-faq {border-top: 1px solid #eee; padding: 18px 0 8px; cursor: pointer;}
        .a-faq h3 {text-transform: none; font-weight: 600; padding-bottom: 8px; position: relative; padding-right: 50px; line-height: 1.35; font-size: 20px;}
        .a-faq > h3:before {content: "✕"; position: absolute; right: 30px; top: 0; transform: rotate(45deg); transition: transform 0.2s ease;}
        .a-faq.active > h3:before {transform: rotate(0deg);}
        .a-faq .blurb p {margin-bottom: 10px;}
        .text-only .section-title {font-weight: 500; margin-bottom: 20px; margin-top: 40px; font-family: "Montserrat", sans-serif; font-size: clamp(22px, 3vw, 32px); text-transform: none;}
        @media screen and (max-width: 800px) {
            #MainContent {padding-top: 80px;}
        }
    </style>
    <div id="MainContent" tabindex="-1">
        <main data-header-color="dark">
            <div class="container text-only">
                <div>
                    <h1>Câu hỏi thường gặp</h1>
                    <div class="blurb">
                        <p>Các câu hỏi hay gặp khi đặt cà phê Robusta, mắc ca và nông sản Tây Nguyên. Nếu chưa thấy câu trả lời, hãy <a href="{{ route('contact') }}">gửi thư cho tiệm</a>.</p>
                    </div>
                    <h2 class="section-title">Đặt hàng và giao hàng</h2>
                    <div class="a-faq">
                        <h3>Làm sao để đặt hàng nông sản?</h3>
                        <div class="blurb" style="display:none;">
                            <p>Gửi thư tại trang Liên hệ hoặc gọi <a href="tel:+84981314516">+84 981 314 516</a>. Ghi rõ sản phẩm, khối lượng và địa chỉ nhận hàng.</p>
                        </div>
                    </div>
                    <div class="a-faq">
                        <h3>Tiệm giao hàng những tỉnh nào?</h3>
                        <div class="blurb" style="display:none;">
                            <p>Tiệm Nhà Duy giao toàn quốc. Thời gian nhận hàng tùy đơn vị vận chuyển và khu vực, thường 1–4 ngày làm việc.</p>
                        </div>
                    </div>
                    <div class="a-faq">
                        <h3>Tôi không nhận được xác nhận đơn?</h3>
                        <div class="blurb" style="display:none;">
                            <p>Kiểm tra hộp thư rác. Nếu vẫn chưa thấy, gửi lại email đặt hàng tới <a href="mailto:support@tiemnhaduy.com">support@tiemnhaduy.com</a> kèm họ tên và số điện thoại.</p>
                        </div>
                    </div>
                    <h2 class="section-title">Cà phê và nông sản</h2>
                    <div class="a-faq">
                        <h3>Cà phê Robusta Gia Lai bảo quản thế nào?</h3>
                        <div class="blurb" style="display:none;">
                            <p>Giữ hạt trong túi zip hoặc hũ kín, nơi khô ráo, tránh nắng. Nên dùng trong 4–8 tuần sau khi mở để giữ hương.</p>
                        </div>
                    </div>
                    <div class="a-faq">
                        <h3>Mắc ca và tiêu đen có bán quanh năm không?</h3>
                        <div class="blurb" style="display:none;">
                            <p>Mắc ca sấy và tiêu đen thường có sẵn. Bơ sáp và sầu riêng theo mùa vụ. Xem <a href="{{ route('san-pham') }}">danh mục sản phẩm</a> hoặc hỏi tiệm trước khi đặt số lớn.</p>
                        </div>
                    </div>
                    <h2 class="section-title">Bán sỉ và hợp tác</h2>
                    <div class="a-faq">
                        <h3>Có bán sỉ cho quán cà phê không?</h3>
                        <div class="blurb" style="display:none;">
                            <p>Có. Tiệm cung cấp cà phê, mắc ca và tiêu đen theo sản lượng. Liên hệ để nhận báo giá sỉ theo mùa vụ.</p>
                        </div>
                    </div>
                    <div class="a-faq">
                        <h3>Có đóng gói quà tặng không?</h3>
                        <div class="blurb" style="display:none;">
                            <p>Có. Tiệm gói set cà phê, mắc ca hoặc nông sản theo yêu cầu. Xem thêm tại trang <a href="{{ route('services') }}">dịch vụ</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        $(".a-faq").click(function () {
            $(this).toggleClass("active");
            $(this).find(".blurb").slideToggle();
        });
    </script>
@endsection
