@extends('layouts.rosta')

@section('title', 'Cà phê, mắc ca, tiêu đen, bơ sáp | Tiệm Nhà Duy')
@section('meta_description', 'Danh mục nông sản Tây Nguyên tại Tiệm Nhà Duy: cà phê Robusta Gia Lai, hạt mắc ca, tiêu đen, bơ sáp và sầu riêng theo mùa.')
@section('og_title', 'Nông sản Tây Nguyên | Tiệm Nhà Duy')
@section('og_description', 'Cà phê Robusta Gia Lai, mắc ca, tiêu đen, bơ sáp và sầu riêng từ vườn Chư Sê. Xem danh mục và đặt hàng trực tiếp.')
@section('og_image', asset('rosta/images/about-us-image.jpg'))
@section('og_image_alt', 'Nông sản Tây Nguyên của Tiệm Nhà Duy: cà phê, mắc ca, tiêu, bơ')
@section('canonical_url', route('san-pham'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "Nông sản Tây Nguyên",
    "description": "Danh mục nông sản Tây Nguyên tại Tiệm Nhà Duy: cà phê Robusta Gia Lai, hạt mắc ca, tiêu đen, bơ sáp và sầu riêng theo mùa.",
    "url": "{{ route('san-pham') }}",
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
            "name": "Sản phẩm",
            "item": "{{ route('san-pham') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    @include('pages.partials.rosta.page-header', ['title' => 'Nông sản Tây Nguyên'])
    <div class="page-services product-catalog">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <p class="section-eyebrow wow fadeInUp">Danh mục sản phẩm</p>
                        <h2 class="wow fadeInUp">Nông sản sạch từ vườn Chư Sê, Gia Lai</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">Mỗi mặt hàng được chọn theo mùa vụ, nguồn gốc rõ ràng, đóng gói để giữ hương vị Tây Nguyên đến tay gia đình bạn.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <article class="service-item product-catalog-item wow fadeInUp">
                        <figure class="product-catalog-image">
                            <img src="{{ asset('rosta/images/produce/thumb-coffee.webp') }}" alt="Cà phê Robusta Gia Lai hạt chắc" width="640" height="480" loading="lazy" decoding="async">
                        </figure>
                        <div class="service-content">
                            <h3>Cà phê Robusta Gia Lai</h3>
                            <p>Hạt chắc, hương đậm, hậu vị rõ. Rang theo lô nhỏ từ vườn Chư Sê, phù hợp pha phin hoặc espresso.</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="service-item product-catalog-item wow fadeInUp" data-wow-delay="0.15s">
                        <figure class="product-catalog-image">
                            <img src="{{ asset('rosta/images/produce/thumb-macadamia.webp') }}" alt="Hạt mắc ca Tây Nguyên" width="640" height="480" loading="lazy" decoding="async">
                        </figure>
                        <div class="service-content">
                            <h3>Hạt mắc ca</h3>
                            <p>Mắc ca sấy hoặc còn vỏ, béo bùi tự nhiên. Phù hợp ăn trực tiếp, làm quà hoặc bán lẻ.</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="service-item product-catalog-item wow fadeInUp" data-wow-delay="0.3s">
                        <figure class="product-catalog-image">
                            <img src="{{ asset('rosta/images/produce/thumb-pepper.webp') }}" alt="Tiêu đen hạt Tây Nguyên" width="640" height="480" loading="lazy" decoding="async">
                        </figure>
                        <div class="service-content">
                            <h3>Tiêu đen Gia Lai</h3>
                            <p>Tiêu hạt chắc, cay nồng, phơi khô theo cách nhà vườn. Dùng cho bếp gia đình hoặc quán ăn.</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="service-item product-catalog-item wow fadeInUp">
                        <figure class="product-catalog-image">
                            <img src="{{ asset('rosta/images/produce/thumb-avocado.webp') }}" alt="Bơ sáp Tây Nguyên" width="640" height="480" loading="lazy" decoding="async">
                        </figure>
                        <div class="service-content">
                            <h3>Bơ sáp Tây Nguyên</h3>
                            <p>Bơ sáp dẻo, cơm vàng, vị béo thanh. Thu hoạch theo mùa tại Gia Lai và các tỉnh Tây Nguyên.</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="service-item product-catalog-item wow fadeInUp" data-wow-delay="0.15s">
                        <figure class="product-catalog-image">
                            <img src="{{ asset('rosta/images/produce/thumb-durian.webp') }}" alt="Sầu riêng múi vàng Tây Nguyên" width="640" height="480" loading="lazy" decoding="async">
                        </figure>
                        <div class="service-content">
                            <h3>Sầu riêng theo mùa</h3>
                            <p>Sầu riêng múi vàng, thơm béo. Chỉ bán đúng vụ, chọn trái già cây, không ép chín.</p>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="service-item product-catalog-item wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-content">
                            <h3>Đặt hàng và tư vấn</h3>
                            <p>Cần số lượng sỉ, đóng gói quà hoặc giao toàn quốc? <a href="{{ route('contact') }}">Gửi thư cho tiệm</a> hoặc xem <a href="{{ route('services') }}">dịch vụ hỗ trợ</a>.</p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
@endsection
