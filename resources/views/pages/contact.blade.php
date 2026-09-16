@extends('layouts.rosta')

@section('title', 'Liên hệ Tiệm Nhà Duy – Macca Gia Lai, Chư Sê')
@section('meta_description', 'Gửi thư đặt cà phê Robusta, mắc ca hoặc hỏi mùa vụ. Tiệm Nhà Duy tại Macca Gia Lai, xã Chư Sê, tỉnh Gia Lai. Xem vị trí trên Google Maps.')
@section('og_title', 'Liên hệ Tiệm Nhà Duy tại Chư Sê, Gia Lai')
@section('og_description', 'Đặt hàng nông sản Tây Nguyên tại Macca Gia Lai, xã Chư Sê. Gửi thư hoặc xem bản đồ để đến tiệm.')
@section('og_image', asset('rosta/images/book-table-image.jpg'))
@section('og_image_alt', 'Liên hệ và bản đồ Tiệm Nhà Duy tại Macca Gia Lai, Chư Sê, Gia Lai')
@section('canonical_url', route('contact'))
@section('meta_keywords', 'Liên hệ Tiệm Nhà Duy, Macca Gia Lai, địa chỉ Chư Sê Gia Lai, bản đồ Tiệm Nhà Duy, đặt cà phê Robusta')

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ContactPage",
    "name": "Liên hệ Tiệm Nhà Duy",
    "description": "Gửi thư đặt cà phê Robusta, mắc ca hoặc hỏi mùa vụ. Tiệm Nhà Duy tại Macca Gia Lai, xã Chư Sê, tỉnh Gia Lai.",
    "url": "{{ route('contact') }}",
    "inLanguage": "vi-VN",
    "mainEntity": {
        "@@id": "{{ url('/') }}#localbusiness"
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "LocalBusiness",
    "@@id": "{{ url('/') }}#localbusiness",
    "name": "Tiệm Nhà Duy",
    "image": "{{ asset('rosta/images/about-us-image.jpg') }}",
    "url": "{{ url('/') }}",
    "telephone": "+84981314516",
    "email": "support@tiemnhaduy.com",
    "priceRange": "$$",
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
    "hasMap": "https://maps.app.goo.gl/7Yj27C915hADd5NR8",
    "areaServed": {
        "@@type": "AdministrativeArea",
        "name": "Gia Lai"
    },
    "openingHoursSpecification": [
        {
            "@@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "08:00",
            "closes": "21:00"
        },
        {
            "@@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Saturday", "Sunday"],
            "opens": "09:00",
            "closes": "22:00"
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
            "name": "Liên hệ",
            "item": "{{ route('contact') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    @include('pages.partials.rosta.page-header', ['title' => 'Liên hệ Tiệm Nhà Duy'])
    <div class="page-contact-us">
        <div class="container">
            <div class="row align-items-stretch">
                <div class="col-lg-5">
                    <div class="contact-information">
                        <div class="section-title">
                            <p class="section-eyebrow wow fadeInUp">Viết thư cho tiệm</p>
                            <h2 class="wow fadeInUp">Gửi yêu cầu, chúng tôi đọc từng lá</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Đặt hàng, hỏi mùa vụ cà phê hay góp ý món quê — điền form bên cạnh, Tiệm Nhà Duy phản hồi trong giờ làm việc.</p>
                        </div>
                        <div class="contact-info-body contact-info-box-2">
                            <div class="contact-info-item">
                                <div class="icon-box"><img src="{{ asset('rosta/images/icon-phone-accent.svg') }}" alt=""></div>
                                <div class="contact-item-content">
                                    <h3>Điện thoại</h3>
                                    <p><a href="tel:+84981314516">+84 981 314 516</a></p>
                                </div>
                            </div>
                            <div class="contact-info-item">
                                <div class="icon-box"><img src="{{ asset('rosta/images/icon-mail-accent.svg') }}" alt=""></div>
                                <div class="contact-item-content">
                                    <h3>Email</h3>
                                    <p><a href="mailto:support@tiemnhaduy.com">support@tiemnhaduy.com</a></p>
                                </div>
                            </div>
                            <div class="contact-info-item">
                                <div class="icon-box"><img src="{{ asset('rosta/images/icon-location.svg') }}" alt=""></div>
                                <div class="contact-item-content">
                                    <h3>Địa chỉ</h3>
                                    <p><a href="https://maps.app.goo.gl/7Yj27C915hADd5NR8" target="_blank" rel="noopener noreferrer">Macca Gia Lai, Nguyễn Văn Linh, xã Chư Sê, tỉnh Gia Lai</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-us-form mail-compose">
                        <div class="contact-form-content">
                            <h3>Soạn thư</h3>
                            <p>Điền đủ họ tên, email và nội dung. Không cần tài khoản.</p>
                        </div>

                        @if (session('status'))
                            <p class="mail-compose-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($errors->any())
                            <ul class="mail-compose-errors" role="alert">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form action="{{ route('contact.send') }}" method="POST" novalidate>
                            @csrf
                            <input type="hidden" name="intent" value="contact">
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <label for="mail-name">Họ tên</label>
                                    <input type="text" name="name" id="mail-name" class="form-control" value="{{ old('name') }}" placeholder="Họ và tên của bạn" autocomplete="name" required>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label for="mail-email">Email</label>
                                    <input type="email" name="email" id="mail-email" class="form-control" value="{{ old('email') }}" placeholder="email@domain.com" autocomplete="email" required>
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <label for="mail-phone">Số điện thoại <span>(không bắt buộc)</span></label>
                                    <input type="tel" name="phone" id="mail-phone" class="form-control" value="{{ old('phone') }}" placeholder="0981 314 516" autocomplete="tel">
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <label for="mail-message">Nội dung</label>
                                    <textarea name="message" id="mail-message" class="form-control" rows="6" placeholder="Bạn muốn hỏi về cà phê, đặt hàng hay hợp tác?" required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-lg-12 mail-compose-actions">
                                    <button type="submit" class="btn-default">Gửi thư</button>
                                    <a class="mail-compose-alt" href="mailto:support@tiemnhaduy.com">Hoặc mở app mail sẵn</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="google-map" aria-labelledby="contact-map-heading">
        <div class="container">
            <div class="section-title">
                <h2 id="contact-map-heading">Vị trí trên bản đồ</h2>
                <p>Macca Gia Lai, Nguyễn Văn Linh, xã Chư Sê, tỉnh Gia Lai. <a href="https://maps.app.goo.gl/7Yj27C915hADd5NR8" target="_blank" rel="noopener noreferrer">Mở Google Maps</a></p>
            </div>
            <figure class="google-map-iframe">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.947638066792!2d108.05991787597776!3d13.721619986667028!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x316e833dc3313e4f%3A0xb234a4637dd5cc19!2sMacca%20Gia%20Lai!5e0!3m2!1svi!2s!4v1789572237512!5m2!1svi!2s"
                    title="Bản đồ Tiệm Nhà Duy tại Macca Gia Lai, xã Chư Sê, tỉnh Gia Lai"
                    width="600"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                ></iframe>
                <figcaption>Tiệm Nhà Duy tại Macca Gia Lai, xã Chư Sê, tỉnh Gia Lai.</figcaption>
            </figure>
        </div>
    </section>
@endsection
