@extends('layouts.rosta')

@section('title', 'Liên hệ')
@section('meta_description', 'Gửi thư cho Tiệm Nhà Duy để nhận tư vấn nông sản Chư Sê – Gia Lai.')
@section('og_image', asset('rosta/images/icon-mail-accent.svg'))
@section('canonical_url', route('contact'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ContactPage",
    "name": "Liên hệ",
    "description": "Gửi thư cho Tiệm Nhà Duy để nhận tư vấn nông sản Chư Sê – Gia Lai.",
    "url": "{{ route('contact') }}",
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
            "name": "Liên hệ",
            "item": "{{ route('contact') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    @include('pages.partials.rosta.page-header', ['title' => 'liên hệ'])
    <div class="page-contact-us">
        <div class="container">
            <div class="row align-items-stretch">
                <div class="col-lg-5">
                    <div class="contact-information">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">viết thư cho tiệm</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Gửi yêu cầu, chúng tôi đọc từng lá</h2>
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
                                    <p><a href="https://maps.app.goo.gl/JXTdYnauTKkTRdbi7" target="_blank" rel="noopener noreferrer">Xã Chư Sê, tỉnh Gia Lai</a></p>
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
                                    <input type="text" name="name" id="mail-name" class="form-control" value="{{ old('name') }}" autocomplete="name" required>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label for="mail-email">Email</label>
                                    <input type="email" name="email" id="mail-email" class="form-control" value="{{ old('email') }}" autocomplete="email" required>
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <label for="mail-phone">Số điện thoại <span>(không bắt buộc)</span></label>
                                    <input type="tel" name="phone" id="mail-phone" class="form-control" value="{{ old('phone') }}" autocomplete="tel">
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <label for="mail-message">Nội dung</label>
                                    <textarea name="message" id="mail-message" class="form-control" rows="6" required>{{ old('message') }}</textarea>
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
@endsection
