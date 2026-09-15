@extends('layouts.rosta')

@section('title', 'Cà phê Tây Nguyên nguyên bản | Tiệm Nhà Duy')
@section('meta_description', 'Khám phá cà phê Tây Nguyên nguyên bản với hương vị đậm đà, từ hạt rang chất lượng đến ly pha chuẩn gu dành cho người yêu cà phê Việt.')
@section('og_image', asset('rosta/images/tiemnhaduy.svg'))
@section('canonical_url', route('home'))

@push('head_preloads')
<link rel="preload" as="image" href="{{ asset('rosta/images/produce/highland-coffee-sm.webp') }}" fetchpriority="high" type="image/webp" imagesrcset="{{ asset('rosta/images/produce/highland-coffee-sm.webp') }} 800w, {{ asset('rosta/images/produce/highland-coffee.webp') }} 1400w" imagesizes="100vw">
@endpush

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Tiệm Nhà Duy",
    "description": "Khám phá cà phê Tây Nguyên nguyên bản với hương vị đậm đà, từ hạt rang chất lượng đến ly pha chuẩn gu dành cho người yêu cà phê Việt.",
    "url": "{{ route('home') }}",
    "inLanguage": "vi-VN"
}
</script>
@endpush

@section('content')

    <!-- Hero Section Start -->
    <div class="hero hero-video" id="main-content">
        <div class="background">
            <picture>
                <source media="(max-width: 767px)" srcset="{{ asset('rosta/images/produce/highland-coffee-sm.webp') }}" type="image/webp">
                <img
                    class="media-kenburns-img"
                    src="{{ asset('rosta/images/produce/highland-coffee.webp') }}"
                    alt="Vườn cà phê Gia Lai, đất đỏ bazan Tây Nguyên"
                    fetchpriority="high"
                    loading="eager"
                    decoding="async"
                    width="1400"
                    height="662"
                >
            </picture>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-10">
                    <!-- Hero Content Start -->
                    <div class="hero-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">nông sản sạch từ tâm, chất lượng đến tay bạn</h3>
                            <h1 class="text-anime-style-3" data-cursor="-opaque">Robusta Gia Lai</h1>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Tiệm Nhà Duy giới thiệu đặc sản Tây Nguyên gồm cà phê robusta, hạt mắc ca béo bùi, tiêu đen nồng thơm và bơ sáp tươi ngon. Chúng tôi tập trung nguồn gốc rõ ràng, hương vị thật và giá trị bền vững cho mỗi gia đình Việt.</p>
                        </div>
                        <!-- Section Title End -->
                        
                        <!-- Hero Button Start -->
                        <div class="hero-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('about') }}" class="btn-default">Khám phá sản phẩm</a>
                            <a href="{{ route('thanh-toan') }}" class="btn-default btn-highlighted">Thanh toán ngay</a>
                        </div>
                        <!-- Hero Button End -->
                    </div>
                    <!-- Hero Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->

    <!-- Scrolling Ticker Section Start -->
    <div class="our-scrolling-ticker">
        <!-- Scrolling Ticker Start -->
        <div class="scrolling-ticker-box">
            <div class="scrolling-content">
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Robusta Gia Lai</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Mắc ca Tây Nguyên</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Tiêu đen</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Bơ sáp</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Sầu riêng</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Cà phê Chư Sê</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Nông sản sạch</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Robusta Gia Lai</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Mắc ca Tây Nguyên</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Tiêu đen</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Bơ sáp</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Sầu riêng</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Cà phê Chư Sê</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Nông sản sạch</span>
            </div>

            <div class="scrolling-content">
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Robusta Gia Lai</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Mắc ca Tây Nguyên</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Tiêu đen</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Bơ sáp</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Sầu riêng</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Cà phê Chư Sê</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Nông sản sạch</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Robusta Gia Lai</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Mắc ca Tây Nguyên</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Tiêu đen</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Bơ sáp</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Sầu riêng</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Cà phê Chư Sê</span>
                <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Nông sản sạch</span>
            </div>
        </div>
        <!-- Scrolling Ticker End -->
    </div>
    <!-- Scrolling Ticker Section End -->

    <!-- About us Section Start -->
    <div class="about-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- About us Content Start -->
                    <div class="about-us-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">về chúng tôi</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Kết nối gia đình Việt qua từng sản phẩm sạch mỗi ngày</h2>
                        </div>
                        <!-- Section Title End -->
                         
                        <!-- About Body List Start -->
                        <div class="about-body-list">
                            <!-- About Body Item Start -->
                            <div class="about-body-item wow fadeInUp" data-wow-delay="0.2s">
                                <div class="icon-box">
                                    <img src="{{ asset('rosta/images/icon-about-body-item-1.svg') }}" alt="About Rosta feature icon">
                                </div>
                                <div class="about-body-list-content">
                                    <h3>Giao hàng toàn quốc</h3>
                                    <p>Đặt mua nhanh các sản phẩm nông sản như cà phê robusta, mắc ca, tiêu và bơ với quy trình đóng gói kỹ, giao hàng tận nơi.</p>
                                </div>
                            </div>
                            <!-- About Body Item End -->
                            
                            <!-- About Body Item Start -->
                            <div class="about-body-item wow fadeInUp" data-wow-delay="0.4s">
                                <div class="icon-box">
                                    <img src="{{ asset('rosta/images/icon-about-body-item-2.svg') }}" alt="About Rosta feature icon">
                                </div>
                                <div class="about-body-list-content">
                                    <h3>Quà tặng và sự kiện</h3>
                                    <p>Chúng tôi nhận tư vấn combo quà tặng nông sản cho doanh nghiệp, gia đình và các dịp lễ tết với hình ảnh chỉn chu.</p>
                                </div>
                            </div>
                            <!-- About Body Item End -->
                        </div>
                        <!-- About Body List End -->
                        
                        <!-- About Us Footer Start -->
                        <div class="about-us-footer wow fadeInUp" data-wow-delay="0.6s">
                            <!-- About Button Start -->
                            <div class="about-btn">
                                <a href="{{ route('about') }}" class="btn-default">Xem thêm về chúng tôi</a>
                            </div>
                            <!-- About Button End -->
                            
                            <!-- Video Play Button Start -->
                            <div class="video-play-button">
                                <a href="{{ route('about') }}" data-cursor-text="Xem">
                                    <i class="fa-solid fa-leaf"></i>
                                </a>
                                <p>về nông sản</p>
                            </div>
                            <!-- Video Play Button End -->
                        </div>
                        <!-- About Us Footer End -->
                    </div>
                    <!-- About us Content End -->
                </div>

                <div class="col-lg-6">
                    <!-- About Us Image Start -->
                    <div class="about-us-image">
                        <!-- About Us Image Start -->
                        <div class="about-us-img">
                            <figure class="image-anime">
                                <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/coffee-cherries.webp') }}" alt="Chùm cà phê chín đỏ trên cây Tây Nguyên" width="1021" height="642" loading="lazy" decoding="async">
                            </figure>
                        </div>
                        <!-- About Us Image End -->
                        
                        <!-- Opening Time Box Start -->
                        <div class="opening-time-box">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <!-- Icon Box End -->
                            
                            <!-- Opening Time Content Start -->
                            <div class="opening-time-content">
                                <h3>Giờ hoạt động</h3>
                                <ul>
                                    <li>Thứ Hai - Thứ Sáu<span>09:30 - 19:30</span></li>
                                    <li>Thứ Bảy<span>10:30 - 17:00</span></li>
                                    <li>Chủ Nhật<span>Online</span></li>
                                </ul>
                            </div>
                            <!-- Opening Time Content End -->
                        </div>
                        <!-- About Menu Box End -->
                    </div>
                    <!-- Opening Time Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- About us Section End -->

    <!-- Why Choose Us Section Start -->
    <div class="why-choose-us light-bg-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Why Choose Content Start -->
                    <div class="why-choose-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Lý do chọn chúng tôi</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Nơi hương vị nông sản Tây Nguyên gặp sự tận tâm</h2>
                        </div>
                        <!-- Section Title End -->

                        <!-- Why Choose Button Start -->
                        <div class="why-choose-btn wow fadeInUp" data-wow-delay="0.2s">
                            <a href="{{ route('contact') }}" class="btn-default">Liên hệ ngay</a>
                        </div>
                        <!-- Why Choose Button End -->
                    </div>
                    <!-- Why Choose Content End -->
                </div>

                <div class="col-lg-6">
                    <!-- Why Choose List Start -->
                    <div class="why-choose-list wow fadeInUp" data-wow-delay="0.2s">
                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-why-choose-1.svg') }}" alt="Rosta service feature icon">
                            </div>
                            <div class="why-choose-item-content">
                                <h3>Nguồn gốc rõ ràng</h3>
                                <p>Cà phê Robusta Gia Lai và các nông sản đều được chọn lọc kỹ, ưu tiên chất lượng thật.</p>
                            </div>
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-why-choose-2.svg') }}" alt="Rosta service feature icon">
                            </div>
                            <div class="why-choose-item-content">
                                <h3>Sản phẩm tươi mới</h3>
                                <p>Mắc ca, tiêu và bơ được thu mua theo mùa vụ, giữ độ tươi ngon và hương vị tự nhiên.</p>
                            </div>
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-why-choose-3.svg') }}" alt="Rosta service feature icon">
                            </div>
                            <div class="why-choose-item-content">
                                <h3>Đóng gói sạch đẹp</h3>
                                <p>Phù hợp dùng gia đình hoặc làm quà biếu tặng với hình thức gọn gàng, lịch sự.</p>
                            </div>
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-why-choose-4.svg') }}" alt="Rosta service feature icon">
                            </div>
                            <div class="why-choose-item-content">
                                <h3>Tư vấn nhanh</h3>
                                <p>Hỗ trợ chọn sản phẩm phù hợp nhu cầu sử dụng, pha chế và bảo quản cho khách hàng mới.</p>
                            </div>
                        </div>
                        <!-- Why Choose Item End -->


                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-why-choose-5.svg') }}" alt="Rosta service feature icon">
                            </div>
                            <div class="why-choose-item-content">
                                <h3>Thân thiện, dễ tiếp cận</h3>
                                <p>Nội dung dễ hiểu, minh bạch giá và hướng dẫn cụ thể để bạn yên tâm khi mua.</p>
                            </div>
                        </div>
                        <!-- Why Choose Item End -->


                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-why-choose-6.svg') }}" alt="Rosta service feature icon">
                            </div>
                            <div class="why-choose-item-content">
                                <h3>Hỗ trợ online mỗi ngày</h3>
                                <p>Bạn có thể liên hệ qua mạng xã hội hoặc điện thoại để đặt hàng và nhận tư vấn nhanh.</p>
                            </div>
                        </div>
                        <!-- Why Choose Item End -->
                    </div>
                    <!-- Why Choose List End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us Section End -->

    <!-- Intro Video Section Start -->
    <div class="intro-video parallaxie" style="text-align: center;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-8">
                    <!-- Intro Video Content Start -->
                    <div class="intro-video-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Hành Trình Phát Triển</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Từ vườn đến bàn ăn với Cà phê robusta gia lai</h2>
                        </div>
                        <!-- Section Title End -->
                    </div>
                    <!-- Intro Video Content End -->
                </div>

                <div class="col-lg-6 col-md-4">
                    <!-- Intro Video Box Start -->
                    <div class="intro-video-box about-intro-video wow fadeInUp" data-wow-delay="0.2s">
                        <div class="video-play-button">
                            <a href="{{ route('about') }}" data-cursor-text="Xem">
                                <i class="fa-solid fa-leaf"></i>
                            </a>
                            <p>từ vườn Gia Lai</p>
                        </div>
                    </div>
                    <!-- Intro Video Box End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Intro Video Counters Start -->
                    <div class="intro-video-counters">
                        <!-- Video Counter Item Start -->
                        <div class="video-counter-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-intro-video-counter-1.svg') }}" alt="Restaurant statistic icon">
                            </div>
                            <div class="video-counter-content">
                                <h2><span class="counter">300</span>+</h2>
                            <p>khách thăm mỗi ngày</p>
                            </div>
                        </div>
                        <!-- Video Counter Item End -->

                        <!-- Video Counter Item Start -->
                        <div class="video-counter-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-intro-video-counter-2.svg') }}" alt="Restaurant statistic icon">
                            </div>
                            <div class="video-counter-content">
                                <h2><span class="counter">50</span></h2>
                            <p>công thức gợi ý</p>
                            </div>
                        </div>
                        <!-- Video Counter Item End -->

                        <!-- Video Counter Item Start -->
                        <div class="video-counter-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-intro-video-counter-3.svg') }}" alt="Restaurant statistic icon">
                            </div>
                            <div class="video-counter-content">
                                <h2><span class="counter">120</span>+</h2>
                                <p>đợt sự kiện đồng hành</p>
                            </div>
                        </div>
                        <!-- Video Counter Item End -->

                        <!-- Video Counter Item Start -->
                        <div class="video-counter-item">
                            <div class="icon-box">
                                <img src="{{ asset('rosta/images/icon-intro-video-counter-4.svg') }}" alt="Restaurant statistic icon">
                            </div>
                            <div class="video-counter-content">
                                <h2><span class="counter">500</span>+</h2>
                                <p>khách hàng hài lòng</p>
                            </div>
                        </div>
                        <!-- Video Counter Item End -->
                    </div>
                    <!-- Intro Video Counters End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Intro Video Section End -->

    <!-- Our Pricing Section Start -->
    <div class="our-pricing">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">bảng giá nổi bật</h3>
                        <h2 class="text-anime-style-3" data-cursor="-opaque">Quality farm produce, fair value for every family</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="our-pricing-box tab-content" id="pricingtab">
                        <!-- Sidebar Our Support Nav start -->
                        <div class="our-support-nav wow fadeInUp" data-wow-delay="0.2s">
                            <ul class="nav nav-tabs" id="mvTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="btn-default btn-highlighted active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-selected="true">Cà phê</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="btn-default btn-highlighted" id="see-food-tab" data-bs-toggle="tab" data-bs-target="#see-food" type="button" role="tab" aria-selected="false">Mắc ca</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="btn-default btn-highlighted" id="desserts-tab" data-bs-toggle="tab" data-bs-target="#desserts" type="button" role="tab" aria-selected="false">Tiêu</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="btn-default btn-highlighted" id="drink-tab" data-bs-toggle="tab" data-bs-target="#drink" type="button" role="tab" aria-selected="false">Bơ</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="btn-default btn-highlighted" id="durian-tab" data-bs-toggle="tab" data-bs-target="#durian" type="button" role="tab" aria-selected="false">Sầu riêng</button>
                                </li>
                            </ul>
                        </div>
                        <!-- Sidebar Our Support Nav End -->

                        <!-- Pricing Boxes Start -->
                        <div class="pricing-boxes tab-pane fade show active" id="all" role="tabpanel">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <!-- Pricing Image Start -->
                                    <div class="pricing-image">
                                        <figure class="image-anime">
                                            <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/coffee-roaster.webp') }}" alt="Hạt cà phê robusta đang rang" width="1200" height="800" loading="lazy" decoding="async">
                                        </figure>
                                    </div>
                                    <!-- Pricing Image End -->
                                </div>

                                <div class="col-lg-6">
                                    <!-- Our Menu List Start -->
                                    <div class="our-menu-list">
                                        <!-- Our Menu Item Start -->
                                        <div class="menu-list-item">
                                            <!-- Our Menu Image Start -->
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-coffee.webp') }}" alt="Cà phê robusta Gia Lai" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <!-- Our Menu Image End -->
        
                                            <!-- Menu Item Body Start -->
                                            <div class="menu-item-body">
                                                <!-- Menu Item Title Start -->
                                                <div class="menu-item-title">
                                                    <h3>Cà phê robusta Gia lai</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <!-- Menu Item Title End -->
        
                                                <!-- Menu Item Content Start -->
                                                <div class="menu-item-content">
                                                    <p>Hương vị đậm, thơm rõ và hậu vị dễ chịu, phù hợp uống hằng ngày hoặc làm quà tặng đặc sản.</p>
                                                </div>
                                                <!-- Menu Item Content End -->
                                            </div>
                                            <!-- Menu Item Body End -->
                                        </div>
                                        
                                        <!-- Our Menu Item Start -->
                                        <div class="menu-list-item">
                                            <!-- Our Menu Image Start -->
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-macadamia.webp') }}" alt="Hạt mắc ca Tây Nguyên" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <!-- Our Menu Image End -->
        
                                            <!-- Menu Item Body Start -->
                                            <div class="menu-item-body">
                                                <!-- Menu Item Title Start -->
                                                <div class="menu-item-title">
                                                    <h3>Hạt mắc ca</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <!-- Menu Item Title End -->
        
                                                <!-- Menu Item Content Start -->
                                                <div class="menu-item-content">
                                    <p>Hạt béo bùi, giàu dinh dưỡng, thích hợp dùng ăn nhẹ hoặc kết hợp cùng ngũ cốc và sữa chua.</p>
                                                </div>
                                                <!-- Our Menu Item End -->
                                                <!-- Menu Item Content End -->
                                            </div>
                                            <!-- Menu Item Body End -->
                                        </div>
                                        <!-- Our Menu Item End -->
                                        
                                        <!-- Our Menu Item Start -->
                                        <div class="menu-list-item">
                                            <!-- Our Menu Image Start -->
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-pepper.webp') }}" alt="Tiêu đen hạt chắc" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <!-- Our Menu Image End -->
        
                                            <!-- Menu Item Body Start -->
                                            <div class="menu-item-body">
                                                <!-- Menu Item Title Start -->
                                                <div class="menu-item-title">
                                                    <h3>Tiêu đen</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <!-- Menu Item Title End -->
        
                                                <!-- Menu Item Content Start -->
                                                <div class="menu-item-content">
                                                    <p>Tiêu đen hạt chắc, thơm nồng tự nhiên, giúp món ăn dậy mùi và đậm đà hơn.</p>
                                                </div>
                                                <!-- Menu Item Content End -->
                                            </div>
                                            <!-- Menu Item Body End -->
                                        </div>
                                        <!-- Our Menu Item End -->

                                        <!-- Our Menu Item Start -->
                                        <div class="menu-list-item">
                                            <!-- Our Menu Image Start -->
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-avocado.webp') }}" alt="Bơ sáp bổ đôi" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <!-- Our Menu Image End -->
        
                                            <!-- Menu Item Body Start -->
                                            <div class="menu-item-body">
                                                <!-- Menu Item Title Start -->
                                                <div class="menu-item-title">
                                                    <h3>Bơ sáp</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <!-- Menu Item Title End -->
        
                                                <!-- Menu Item Content Start -->
                                                <div class="menu-item-content">
                                                    <p>Bơ sáp dẻo mịn, vị béo ngậy tự nhiên, phù hợp làm sinh tố, ăn cùng bánh mì hoặc salad.</p>
                                                </div>
                                                <!-- Menu Item Content End -->
                                            </div>
                                            <!-- Menu Item Body End -->
                                        </div>
                                        <!-- Our Menu Item End -->

                                        <!-- Our Menu Item Start -->
                                        <div class="menu-list-item">
                                            <!-- Our Menu Image Start -->
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-durian.webp') }}" alt="Sầu riêng múi vàng" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <!-- Our Menu Image End -->
        
                                            <!-- Menu Item Body Start -->
                                            <div class="menu-item-body">
                                                <!-- Menu Item Title Start -->
                                                <div class="menu-item-title">
                                                    <h3>Sầu riêng</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <!-- Menu Item Title End -->
        
                                                <!-- Menu Item Content Start -->
                                                <div class="menu-item-content">
                                                    <p>Sầu riêng thơm đậm, múi vàng dẻo và ngọt béo, phù hợp cho khách yêu thích trái cây đặc sản.</p>
                                                </div>
                                                <!-- Menu Item Content End -->
                                            </div>
                                            <!-- Menu Item Body End -->
                                        </div>
                                        <!-- Our Menu Item End -->
                                    </div>
                                    <!-- Our Menu List End -->
                                </div>
                            </div>
                        </div>
                        <!-- Pricing Boxes End -->
                        
                        <!-- Pricing Boxes Start -->
                        <div class="pricing-boxes tab-pane fade" id="see-food" role="tabpanel">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <!-- Pricing Image Start -->
                                    <div class="pricing-image">
                                        <figure class="image-anime">
                                            <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/macadamia.webp') }}" alt="Hạt mắc ca Tây Nguyên" width="800" height="800" loading="lazy" decoding="async">
                                        </figure>
                                    </div>
                                    <!-- Pricing Image End -->
                                </div>

                                <div class="col-lg-6">
                                    <!-- Our Menu List Start -->
                                    <div class="our-menu-list">
                                        <div class="menu-list-item">
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-macadamia.webp') }}" alt="Hạt mắc ca sấy Tây Nguyên" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <div class="menu-item-body">
                                                <div class="menu-item-title">
                                                    <h3>Hạt mắc ca sấy</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <div class="menu-item-content">
                                                    <p>Hạt mắc ca béo bùi, giàu dinh dưỡng, thích hợp ăn vặt lành mạnh cho cả nhà.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="menu-list-item">
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/macadamia-hand.webp') }}" alt="Hạt mắc ca nhân còn vỏ" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <div class="menu-item-body">
                                                <div class="menu-item-title">
                                                    <h3>Hạt mắc ca còn vỏ</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <div class="menu-item-content">
                                                    <p>Hạt chắc, vỏ mỏng, vị béo tự nhiên đặc trưng của mắc ca Tây Nguyên.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Our Menu List End -->
                                </div>
                            </div>
                        </div>
                        <!-- Pricing Boxes End -->

                        <!-- Pricing Boxes Start -->
                        <div class="pricing-boxes tab-pane fade" id="desserts" role="tabpanel">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="pricing-image">
                                        <figure class="image-anime">
                                            <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/peppercorns.webp') }}" alt="Tiêu đen hạt chắc Tây Nguyên" width="900" height="900" loading="lazy" decoding="async">
                                        </figure>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="our-menu-list">
                                        <div class="menu-list-item">
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-pepper.webp') }}" alt="Tiêu đen hạt chắc" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <div class="menu-item-body">
                                                <div class="menu-item-title">
                                                    <h3>Tiêu đen hạt</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <div class="menu-item-content">
                                                    <p>Tiêu đen hạt chắc, thơm nồng tự nhiên, giúp món ăn dậy mùi và đậm đà hơn.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="menu-list-item">
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/peppercorns.webp') }}" alt="Tiêu đen phơi khô Gia Lai" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <div class="menu-item-body">
                                                <div class="menu-item-title">
                                                    <h3>Tiêu đen hữu cơ</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <div class="menu-item-content">
                                                    <p>Tiêu phơi khô theo cách nhà vườn Tây Nguyên, cay rõ, hương bền.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pricing-boxes tab-pane fade" id="drink" role="tabpanel">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="pricing-image">
                                        <figure class="image-anime">
                                            <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/avocado.webp') }}" alt="Bơ sáp bổ đôi" width="1200" height="799" loading="lazy" decoding="async">
                                        </figure>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="our-menu-list">
                                        <div class="menu-list-item">
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-avocado.webp') }}" alt="Bơ sáp Tây Nguyên" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <div class="menu-item-body">
                                                <div class="menu-item-title">
                                                    <h3>Bơ sáp</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <div class="menu-item-content">
                                                    <p>Bơ sáp dẻo mịn, vị béo ngậy tự nhiên, phù hợp làm sinh tố, ăn cùng bánh mì hoặc salad.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="menu-list-item">
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/avocado.webp') }}" alt="Bơ sáp cơm vàng" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <div class="menu-item-body">
                                                <div class="menu-item-title">
                                                    <h3>Bơ sáp theo mùa</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <div class="menu-item-content">
                                                    <p>Thu hoạch tại Gia Lai và Tây Nguyên, cơm vàng, béo thanh, không xơ.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pricing Boxes End -->

                        <div class="pricing-boxes tab-pane fade" id="durian" role="tabpanel">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="pricing-image">
                                        <figure class="image-anime">
                                            <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/durian.webp') }}" alt="Sầu riêng múi vàng Tây Nguyên" width="1100" height="1554" loading="lazy" decoding="async">
                                        </figure>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="our-menu-list">
                                        <div class="menu-list-item">
                                            <div class="menu-list-image">
                                                <figure>
                                                    <img src="{{ asset('rosta/images/produce/thumb-durian.webp') }}" alt="Sầu riêng múi vàng" width="480" height="480" loading="lazy" decoding="async">
                                                </figure>
                                            </div>
                                            <div class="menu-item-body">
                                                <div class="menu-item-title">
                                                    <h3>Sầu riêng</h3>
                                                    <hr>
                                                    <span>160.000đ</span>
                                                </div>
                                                <div class="menu-item-content">
                                                    <p>Sầu riêng thơm đậm, múi vàng dẻo và ngọt béo, phù hợp cho khách yêu thích trái cây đặc sản.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-footer-text wow fadeInUp" data-wow-delay="0.2s">
                            <p>Bạn đang tìm nông sản sạch? <a href="{{ route('thanh-toan') }}">Liên hệ đặt hàng ngay!</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Pricing Section End -->

    <!-- Interactive Process Layout Start -->
    <div class="interactive interactive-process-layout">
        <!-- Interactive Process Wrapper Start -->
        <div class="interactive-interactive-process-wrapper interactive-wrapper">
            <div class="interactive-con">
                <!-- Interactive Inner Grid Start -->
                <div watch-visibility="" class="arches scheme-light visible" style="background-color:#191919">
                    <div class="container">
                        <div class="text">
                            <div class="inner">
                                <h2 class="blinds-text" aria-label="Sản phẩm">
                                    <div class="blinds-text-wrapper">
                                        <div class="blinds-word" aria-hidden="true">
                                            <span class="char-clip"><span style="--i:0ms">S</span></span>
                                            <span class="char-clip"><span style="--i:20ms">a</span></span>
                                            <span class="char-clip"><span style="--i:40ms">n</span></span>
                                            <span class="char-clip"><span style="--i:60ms">&nbsp;</span></span>
                                        </div>
                                        <div class="blinds-word" aria-hidden="true">
                                            <span class="char-clip"><span style="--i:0ms">p</span></span>
                                            <span class="char-clip"><span style="--i:20ms">h</span></span>
                                            <span class="char-clip"><span style="--i:40ms">a</span></span>
                                            <span class="char-clip"><span style="--i:60ms">m</span></span>
                                        </div>
                                    </div>
                                </h2>
                                <div data-reset="true" data-delay="200" reveal="" data-sr-id="7" style="visibility: visible; opacity: 1; transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1); transition: all, opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s;">
                                    <p>Khám phá bộ sưu tập nông sản mới từ vùng đất Gia Lai và Tây Nguyên. Cập nhật sản phẩm mới mỗi tuần qua bản tin của Tiệm Nhà Duy.</p>
                                </div>
                                <a data-reset="true" data-delay="400" reveal="" class="button" href="{{ route('services') }}" data-sr-id="8" style="visibility: visible; opacity: 1; transform: matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1); transition: all, opacity 0.6s ease-out 0.4s, transform 0.6s ease-out 0.4s;">Xem danh mục sản phẩm</a>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!-- Interactive Inner Grid End -->

                <!-- Interactive Process Image Start -->
                <div class="interactive-process-list-image video-split">
                    <div class="interactive-process-image img-0 show">
                        <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/highland-coffee.webp') }}" alt="Vườn cà phê Chư Sê - Gia Lai" width="1400" height="662" loading="lazy" decoding="async">
                        <div class="interactive-process-caption">
                            <h3>Chư Sê - Gia Lai</h3>
                            <p>Vùng đất nổi tiếng với cà phê robusta hạt chắc, hương đậm và hậu vị rõ nét, rất được yêu thích tại Việt Nam.</p>
                            <a href="{{ route('services') }}">Xem cà phê robusta →</a>
                        </div>
                    </div>
                    <div class="interactive-process-image img-1">
                        <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/macadamia.webp') }}" alt="Hạt mắc ca Tây Nguyên" width="800" height="800" loading="lazy" decoding="async">
                        <div class="interactive-process-caption">
                            <h3>Mắc ca Tây Nguyên</h3>
                            <p>Hạt mắc ca được chọn lọc, béo bùi tự nhiên, thích hợp ăn trực tiếp hoặc kết hợp trong các khẩu phần dinh dưỡng.</p>
                            <a href="{{ route('services') }}">Xem sản phẩm mắc ca →</a>
                        </div>
                    </div>
                    <div class="interactive-process-image img-2">
                        <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/avocado.webp') }}" alt="Bơ sáp Tây Nguyên" width="1200" height="799" loading="lazy" decoding="async">
                        <div class="interactive-process-caption">
                            <h3>Bơ sáp Tây Nguyên</h3>
                            <p>Bơ sáp dẻo, cơm vàng, vị béo thanh. Thu hoạch theo mùa tại Gia Lai và các tỉnh Tây Nguyên.</p>
                            <a href="{{ route('services') }}">Xem bơ sáp →</a>
                        </div>
                    </div>
                    <div class="interactive-process-image img-3">
                        <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/peppercorns.webp') }}" alt="Tiêu đen hạt chắc Tây Nguyên" width="900" height="900" loading="lazy" decoding="async">
                        <div class="interactive-process-caption">
                            <h3>Tiêu đen Gia Lai</h3>
                            <p>Tiêu hạt chắc, cay nồng tự nhiên, phơi khô theo cách truyền thống của nhà vườn Tây Nguyên.</p>
                            <a href="{{ route('services') }}">Xem tiêu đen →</a>
                        </div>
                    </div>
                </div>
                <!-- Interactive Process Image End -->
            </div>
        </div>
        <!-- Interactive Process Wrapper End -->
    </div>
    <!-- Interactive Process Layout End -->

    <!-- Our Offers Section Start -->
    <div class="our-offers">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <!-- Our Offers Content Start -->
                    <div class="our-offers-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">chúng tôi cung cấp gì?</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Sản phẩm cho nhu cầu thưởng thức, quà tặng và lưu niệm</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Chúng tôi cung cấp cà phê, mắc ca, tiêu và bơ phù hợp để dùng hằng ngày, làm quà tặng ý nghĩa hoặc chọn làm sản phẩm lưu niệm đặc trưng.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Offer Accordian Start -->
                        <div class="offers-accordion" id="offer-accordion">
                            <!-- Offer Accordian Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.4s">
                                <h2 class="accordion-header" id="offersheading1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#offerscollapse1" aria-expanded="true" aria-controls="offerscollapse1">
                                        Combo gia đình
                                    </button>
                                </h2>
                                <div id="offerscollapse1" class="accordion-collapse collapse show" aria-labelledby="offersheading1" data-bs-parent="#offer-accordion">
                                    <div class="accordion-body">
                                        <p>Gói sản phẩm tổng hợp cà phê robusta, mắc ca, tiêu và bơ phù hợp sử dụng hằng ngày.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Offer Accordian Item End -->
                            
                            <!-- Offer Accordian Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.6s">
                                <h2 class="accordion-header" id="offersheading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#offerscollapse2" aria-expanded="true" aria-controls="offerscollapse2">
                                        Combo quà tặng
                                    </button>
                                </h2>
                                <div id="offerscollapse2" class="accordion-collapse collapse" aria-labelledby="offersheading2" data-bs-parent="#offer-accordion">
                                    <div class="accordion-body">
                                        <p>Đóng gói lịch sự, phù hợp làm quà cho đối tác, người thân trong các dịp quan trọng.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Offer Accordian Item End -->
                            
                            <!-- Offer Accordian Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.8s">
                                <h2 class="accordion-header" id="offersheading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#offerscollapse3" aria-expanded="true" aria-controls="offerscollapse3">
                                        Đặt hàng định kỳ
                                    </button>
                                </h2>
                                <div id="offerscollapse3" class="accordion-collapse collapse" aria-labelledby="offersheading3" data-bs-parent="#offer-accordion">
                                    <div class="accordion-body">
                                        <p>Hỗ trợ đơn hàng theo tuần/tháng để bạn luôn có nông sản tươi mới, ổn định chất lượng.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Offer Accordian Item End -->
                        </div>
                        <!-- Offer Accordian End -->

                        <!-- Offer Button Start -->
                        <div class="offer-button wow fadeInUp" data-wow-delay="1s">
                            <a href="{{ route('thanh-toan') }}" class="btn-default">Đặt lịch tư vấn ngay</a>
                        </div>
                        <!-- Offer Button End -->
                    </div>
                    <!-- Our Offers Content End -->
                </div>
                
                <div class="col-lg-7">
                    <!-- Our Offer Images Start -->
                    <div class="our-offers-images">
                        <!-- Offer Image Start -->
                        <div class="offer-image">
                            <figure class="image-anime">
                                <img src="{{ asset('rosta/images/produce/phin-coffee.webp') }}" alt="Pha cà phê phin Tây Nguyên" width="1200" height="796" loading="lazy" decoding="async">
                            </figure>
                        </div>
                        <!-- Offer Image End -->
                        
                        <!-- Offer Circle Image 1 Start -->
                        <div class="offer-circle-image-1">
                            <figure class="image-anime">
                                <img src="{{ asset('rosta/images/produce/macadamia-hand.webp') }}" alt="Hạt mắc ca trên tay" width="1000" height="1333" loading="lazy" decoding="async">
                            </figure>
                        </div>  
                        <!-- Offer Circle Image 1 End -->
                        
                        <!-- Offer Circle Image 2 Start -->
                        <div class="offer-circle-image-2">
                            <figure class="image-anime">
                                <img src="{{ asset('rosta/images/produce/avocado.webp') }}" alt="Bơ sáp Tây Nguyên" width="1200" height="799" loading="lazy" decoding="async">
                            </figure>
                        </div>
                        <!-- Offer Circle Image 2 End -->
                    </div>
                    <!-- Our Offer Images End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Offers Section End -->

    <div watch-visibility="" class="doubleSplit visible">
        <div class="container">
            <div class="a-split">
                <div class="background">
                    <div class="blur"></div>
                    <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/coffee-cherries.webp') }}" alt="Cà phê chín trên cây Chư Sê Gia Lai" width="1021" height="642" loading="lazy" decoding="async">
                </div>
                <div class="text">
                    <h2>Khám phá</h2>
                    <p>Mỗi tuần chúng tôi cập nhật cà phê robusta chất lượng cao từ Chư Sê Gia Lai, giúp bạn bắt đầu ngày mới đầy năng lượng.</p>
                    <a href="{{ route('services') }}">Xem bộ sưu tập nông sản</a>
                </div>
            </div>

            <div class="a-split">
                <div class="background">
                    <div class="blur"></div>
                    <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/phin-coffee.webp') }}" alt="Pha cà phê phin tại Tây Nguyên" width="1200" height="796" loading="lazy" decoding="async">
                </div>
                <div class="text">
                    <h2>phát huy</h2>
                    <p>Nếu bạn cần nguồn hàng ổn định cho quán cà phê, cửa hàng nông sản hoặc kênh phân phối, chúng tôi sẵn sàng đồng hành với chính sách linh hoạt.</p>
                    <a href="{{ route('about') }}">Tìm hiểu thêm về Tiệm Nhà Duy</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Testimonials Section Start -->
    <div class="our-testimonials parallaxie">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">cảm nhận khách hàng</h3>
                        <h2 class="text-anime-style-3" data-cursor="-opaque">Người đã dùng sẽ kể cho bạn nghe</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Testimonial Slider Start -->
                    <div class="testimonial-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper" data-cursor-text="Drag">
                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-content">
                                            <p>“ Tôi rất an tâm khi đặt mua Cà phê robusta gia lai tại Tiệm Nhà Duy. Hương vị đậm, giao hàng nhanh và chất lượng đúng như giới thiệu. </p>
                                        </div>
                                        <div class="author-info">
                                            <p>Chị Lan, chủ quán cà phê tại Pleiku</p>
                                        </div>                                    
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->
                    
                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-content">
                                            <p>“ Gia đình tôi đặt mắc ca, tiêu đen và bơ thường xuyên. Sản phẩm tươi mới, đóng gói đẹp, rất phù hợp để dùng và tặng người thân. </p>
                                        </div>
                                        <div class="author-info">
                                            <p>Anh Hiếu, khách hàng thân thiết</p>
                                        </div>                                    
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->                              
                            </div>
                            <div class="testimonial-btn">
                                <div class="testimonial-btn-prev"></div>
                                <div class="testimonial-btn-next"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Slider End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Testimonials Section End -->

    <!-- CTA Box Section Start -->
    <div watch-visibility="" class="textSplit scheme- align-right visible" style="background-color:#eee9df">
        <div class="container">
            <div class="text">
                <div class="inner">
                    <h2 class="blinds-text">nông sản<br>Tây Nguyên</h2>
                    <div>
                        <p>Tiệm Nhà Duy chọn lọc cà phê robusta Gia Lai, mắc ca, tiêu đen, bơ sáp và sầu riêng theo mùa vụ. Mỗi sản phẩm giữ hương vị thật của vùng cao nguyên.</p>
                        <p>Xem danh mục và đặt hàng trực tiếp, không qua trung gian.</p>
                    </div>
                    <a class="button" href="{{ route('services') }}">Xem nông sản Tây Nguyên</a>
                </div>
            </div>
            <div class="media">
                <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/durian.webp') }}" alt="Sầu riêng múi vàng đặc sản" width="1100" height="1554" loading="lazy" decoding="async">
            </div>
        </div>
    </div>
    <!-- CTA Box Section End -->
@endsection
