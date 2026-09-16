<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $seoTitle = trim($__env->yieldContent('title', 'Nông sản Tây Nguyên | Tiệm Nhà Duy'));
        $seoDescription = trim($__env->yieldContent('meta_description', 'Tiệm Nhà Duy cung cấp cà phê Robusta Gia Lai, mắc ca, tiêu đen và bơ sáp — nông sản sạch từ Chư Sê, Tây Nguyên.'));
        $seoCanonical = trim($__env->yieldContent('canonical_url', url()->current()));
        $seoOgTitle = trim($__env->yieldContent('og_title', $seoTitle));
        $seoOgDescription = trim($__env->yieldContent('og_description', $seoDescription));
        $seoOgImage = trim($__env->yieldContent('og_image', asset('rosta/images/about-us-image.jpg')));
        $seoOgImageAlt = trim($__env->yieldContent('og_image_alt', 'Tiệm Nhà Duy — nông sản Tây Nguyên tại Chư Sê, Gia Lai'));
        $seoOgImageType = trim($__env->yieldContent('og_image_type', 'image/jpeg'));
        $seoTwitterImage = trim($__env->yieldContent('twitter_image', $seoOgImage));
    @endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="@yield('meta_keywords', 'Tiệm Nhà Duy, cà phê Robusta Gia Lai, nông sản Tây Nguyên, mắc ca, tiêu đen, bơ sáp, Chư Sê')">
    <meta name="robots" content="@yield('meta_robots', 'index,follow')">
    <meta name="author" content="Tiệm Nhà Duy">
    <link rel="canonical" href="{{ $seoCanonical }}">

    <meta property="og:locale" content="vi_VN">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Tiệm Nhà Duy">
    <meta property="og:title" content="{{ $seoOgTitle }}">
    <meta property="og:description" content="{{ $seoOgDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta property="og:image:secure_url" content="{{ $seoOgImage }}">
    <meta property="og:image:alt" content="{{ $seoOgImageAlt }}">
    <meta property="og:image:type" content="{{ $seoOgImageType }}">
    <meta property="og:image:width" content="@yield('og_image_width', '1200')">
    <meta property="og:image:height" content="@yield('og_image_height', '630')">

    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="{{ $seoOgTitle }}">
    <meta name="twitter:description" content="{{ $seoOgDescription }}">
    <meta name="twitter:image" content="{{ $seoTwitterImage }}">
    <meta name="twitter:image:alt" content="{{ $seoOgImageAlt }}">
    @stack('head_preloads')
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "Tiệm Nhà Duy",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('rosta/images/tiemnhaduy.svg') }}",
            "image": "{{ asset('rosta/images/about-us-image.jpg') }}",
            "email": "support@tiemnhaduy.com",
            "telephone": "+84981314516",
            "address": {
                "@@type": "PostalAddress",
                "streetAddress": "Nguyễn Văn Linh",
                "addressLocality": "Chư Sê",
                "addressRegion": "Gia Lai",
                "postalCode": "61906",
                "addressCountry": "VN"
            },
            "geo": {
                "@@type": "GeoCoordinates",
                "latitude": 13.72162,
                "longitude": 108.059918
            },
            "hasMap": "https://maps.app.goo.gl/7Yj27C915hADd5NR8"
        }
    </script>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebSite",
            "name": "Tiệm Nhà Duy",
            "url": "{{ url('/') }}",
            "inLanguage": "vi-VN"
        }
    </script>
    @stack('structured_data')
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('rosta/images/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('rosta/images/favicon_io/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('rosta/images/favicon_io/favicon-16x16.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('rosta/images/favicon_io/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('rosta/images/favicon_io/site.webmanifest') }}">
    <link rel="preload" href="{{ asset('rosta/webfonts/Room-205.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('rosta/webfonts/forum-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    @php
        $criticalStylesheets = [
            'rosta/css/bootstrap.min.css',
            'rosta/css/custom.css',
        ];
        $deferredStylesheets = [
            'rosta/css/slicknav.min.css',
            'rosta/css/swiper-bundle.min.css',
            'rosta/css/all.min.css',
            'rosta/css/animate.css',
            'rosta/css/magnific-popup.css',
            'rosta/css/mousecursor.css',
        ];
    @endphp
    @foreach ($criticalStylesheets as $stylesheet)
        @php
            $stylesheetVersion = file_exists(public_path($stylesheet)) ? filemtime(public_path($stylesheet)) : null;
            $stylesheetUrl = asset($stylesheet) . ($stylesheetVersion ? '?v=' . $stylesheetVersion : '');
        @endphp
        <link rel="stylesheet" href="{{ $stylesheetUrl }}">
    @endforeach
    @foreach ($deferredStylesheets as $stylesheet)
        @php
            $stylesheetVersion = file_exists(public_path($stylesheet)) ? filemtime(public_path($stylesheet)) : null;
            $stylesheetUrl = asset($stylesheet) . ($stylesheetVersion ? '?v=' . $stylesheetVersion : '');
        @endphp
        <link rel="preload" href="{{ $stylesheetUrl }}" as="style">
        <link rel="stylesheet" href="{{ $stylesheetUrl }}" media="print" onload="this.media='all'">
        <noscript><link rel="stylesheet" href="{{ $stylesheetUrl }}"></noscript>
    @endforeach
    <style>
        header.main-header .header-sticky,
        header.main-header .header-sticky.active {
            background: #FBFAF3 !important;
            border-bottom: 1px solid rgba(17, 17, 17, 0.08) !important;
            box-shadow: 0 8px 24px rgba(18, 29, 35, 0.06) !important;
        }
        .main-menu ul li.nav-item a,
        .main-menu ul li.nav-item.submenu > a {
            color: #1a1a1a !important;
            font-family: "Montserrat", sans-serif !important;
            text-transform: none !important;
            letter-spacing: 0.01em !important;
            font-weight: 500 !important;
            white-space: nowrap;
        }
        .main-menu ul li.nav-item a:hover,
        .main-menu ul li.nav-item a:focus {
            color: #8b6244 !important;
        }
        body:not(.is-home-page) .header-btn .btn-default,
        body:not(.is-home-page) .header-btn .btn-default.btn-highlighted {
            color: #111 !important;
            border-color: #111 !important;
            background: transparent !important;
        }
        body:not(.is-home-page) .header-btn .btn-default:hover,
        body:not(.is-home-page) .header-btn .btn-default.btn-highlighted:hover {
            color: #fff !important;
            background: #111 !important;
        }
        body:not(.is-home-page) .header-sidebar-btn .btn-popup {
            filter: invert(1);
        }
        .navbar-brand img {
            filter: brightness(0) saturate(100%) !important;
        }
        body:not(.is-home-page) .onyx-signature svg #SigAnim path,
        body:not(.is-home-page) .navbar-brand svg #anim path {
            stroke: #111 !important;
            fill: #111 !important;
        }
        .navbar-toggle a.slicknav_btn {
            background-color: transparent !important;
            background-image: none !important;
            border-radius: 8px;
        }
        .slicknav_btn,
        .slicknav_menu .slicknav_btn {
            background: transparent !important;
            background-color: transparent !important;
            background-image: none !important;
        }
        .slicknav_icon .slicknav_icon-bar {
            background-color: #111 !important;
        }
        body:not(.is-home-page) .navbar-toggle a.slicknav_btn:focus,
        body:not(.is-home-page) .navbar-toggle a.slicknav_btn:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body class="onyx-theme {{ request()->routeIs('home') ? 'is-home-page' : 'is-inner-page' }}">
    <a class="skip-link" href="#main-content">Chuyển đến nội dung chính</a>
    @include('pages.partials.rosta.preloader')
    @include('pages.partials.rosta.ticker')
    @include('pages.partials.rosta.header')
    <main id="main-content">
        @yield('content')
    </main>
    @include('pages.partials.rosta.footer')
    @include('pages.partials.rosta.scripts')
</body>
</html>
