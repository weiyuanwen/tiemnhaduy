@extends('layouts.rosta')

@section('title', 'Giao hàng, bán sỉ nông sản Tây Nguyên | Tiệm Nhà Duy')
@section('meta_description', 'Tiệm Nhà Duy giao nông sản toàn quốc, bán sỉ cho quán và đại lý, tư vấn chọn cà phê Robusta, mắc ca, tiêu đen và đóng gói quà.')
@section('og_title', 'Dịch vụ nông sản Tiệm Nhà Duy')
@section('og_description', 'Giao hàng toàn quốc, bán sỉ, tư vấn chọn sản phẩm và đóng gói quà từ nông sản Chư Sê – Gia Lai.')
@section('og_image', asset('rosta/images/our-approach-image.jpg'))
@section('og_image_alt', 'Dịch vụ giao hàng và bán sỉ nông sản Tiệm Nhà Duy')
@section('canonical_url', route('services'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Dịch vụ nông sản Tiệm Nhà Duy",
    "description": "Tiệm Nhà Duy giao nông sản toàn quốc, bán sỉ cho quán và đại lý, tư vấn chọn cà phê Robusta, mắc ca, tiêu đen và đóng gói quà.",
    "url": "{{ route('services') }}",
    "inLanguage": "vi-VN"
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
            "name": "Dịch vụ",
            "item": "{{ route('services') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    @include('pages.partials.rosta.page-header', ['title' => 'Dịch vụ nông sản'])
    <div class="page-services">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <p class="section-eyebrow wow fadeInUp">Hỗ trợ mua hàng</p>
                        <h2 class="wow fadeInUp">Từ vườn Gia Lai đến tay bạn</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">Không chỉ bán nông sản. Tiệm Nhà Duy hỗ trợ giao hàng, bán sỉ và chọn mặt hàng đúng nhu cầu gia đình hay quán.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp">
                        <div class="icon-box"><img src="{{ asset('rosta/images/icon-service-1.svg') }}" alt=""></div>
                        <div class="service-content">
                            <h3>Giao hàng toàn quốc</h3>
                            <p>Đóng gói chắc, gửi nhanh cà phê, mắc ca, tiêu đen và nông sản theo mùa đến nhà bạn.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="icon-box"><img src="{{ asset('rosta/images/icon-service-2.svg') }}" alt=""></div>
                        <div class="service-content">
                            <h3>Bán sỉ cho quán và đại lý</h3>
                            <p>Nguồn hàng ổn định cho quán cà phê, cửa hàng nông sản và kênh phân phối. Giá theo sản lượng.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="icon-box"><img src="{{ asset('rosta/images/icon-service-3.svg') }}" alt=""></div>
                        <div class="service-content">
                            <h3>Tư vấn chọn sản phẩm</h3>
                            <p>Gợi ý cà phê Robusta, mắc ca hay tiêu đen phù hợp khẩu vị, ngân sách và mục đích sử dụng.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp">
                        <div class="icon-box"><img src="{{ asset('rosta/images/icon-our-mission.svg') }}" alt=""></div>
                        <div class="service-content">
                            <h3>Đóng gói quà tặng</h3>
                            <p>Gói nông sản sạch thành set quà Tết, quà đối tác hoặc quà quê gửi người thân.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="icon-box"><img src="{{ asset('rosta/images/icon-our-vision.svg') }}" alt=""></div>
                        <div class="service-content">
                            <h3>Nguồn gốc rõ ràng</h3>
                            <p>Kể được vườn, mùa vụ và cách bảo quản. Xem <a href="{{ route('san-pham') }}">danh mục nông sản</a> trước khi đặt.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="icon-box"><img src="{{ asset('rosta/images/icon-mail-accent.svg') }}" alt=""></div>
                        <div class="service-content">
                            <h3>Hỗ trợ đặt hàng</h3>
                            <p>Nhắn tiệm qua form <a href="{{ route('contact') }}">liên hệ</a> để hỏi tồn kho, giá sỉ hoặc lịch giao.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
