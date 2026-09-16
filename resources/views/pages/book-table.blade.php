@extends('layouts.rosta')

@section('title', 'Tiệm Nhà Duy | Thanh Toán QR Nhanh')
@section('meta_description', 'Thanh toán nhanh tại Tiệm Nhà Duy với mã QR tự động, an toàn và tiện lợi cho đơn hàng nông sản sạch.')
@section('meta_keywords', 'thanh toán tiệm nhà duy, QR chuyển khoản, cà phê robusta chư sê gia lai, nông sản sạch')
@section('og_image', asset('rosta/images/favicon_io/android-chrome-512x512.png'))
@section('canonical_url', route('thanh-toan'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Thanh toán QR nhanh",
    "description": "Thanh toán nhanh tại Tiệm Nhà Duy với mã QR tự động, an toàn và tiện lợi cho đơn hàng nông sản sạch.",
    "url": "{{ route('thanh-toan') }}",
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
            "name": "Thanh toán QR nhanh",
            "item": "{{ route('thanh-toan') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    <div class="page-loading-overlay" id="pageLoadingOverlay" style="display:none;">
        <div class="overlay-loading-box">
            <span class="overlay-loading-icon" aria-hidden="true"></span>
            <p>Vui lòng đợi...</p>
        </div>
    </div>
    <style>
        #MainContent {
            padding-top: 120px;
            padding-bottom: 90px;
        }
        .container.text-only {
            max-width: 800px;
            color: #000;
        }
        .payment-content {
            max-width: 760px;
            margin: 0 auto;
            text-align: center;
        }
        .text-only h1 {
            font-size: 7vw;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: 400;
            margin-bottom: 20px;
            margin-top: 40px;
            font-family: "Room-205";
            font-size: 50px;
        }
        .qr-result .section-title {
            text-align: center;
        }
        .payment-form-wrap {
            border-top: 1px solid #eee;
            padding-top: 24px;
            margin-top: 18px;
        }
        .input-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #222;
            margin-bottom: 8px;
            letter-spacing: 0.02em;
            text-align: left;
        }
        #paymentForm .form-control {
            border: 1px solid #ddd;
            min-height: 56px;
            border-radius: 0;
            padding: 10px 18px;
            font-size: 16px;
        }
        .input-hint {
            margin: 8px 0 0;
            font-size: 13px;
            color: #6f6f6f;
            text-align: left;
        }
        .qr-submit-btn {
            min-width: 190px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
        }
        .btn-loader {
            width: 17px;
            height: 17px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #fff;
            display: none;
            animation: spin 0.8s linear infinite;
        }
        .qr-submit-btn.is-loading {
            opacity: 0.85;
            pointer-events: none;
        }
        .qr-submit-btn.is-loading .btn-loader {
            display: inline-block;
        }
        .page-loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(16, 16, 16, 0.55);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }
        .overlay-loading-box {
            min-width: 220px;
            padding: 18px 22px;
            background: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.2);
        }
        .overlay-loading-box p {
            margin: 0;
            font-size: 15px;
            font-weight: 500;
            color: #222;
        }
        .overlay-loading-icon {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-top-color: #111;
            display: inline-block;
            animation: spin 0.8s linear infinite;
        }
        .qr-card-badge.is-paid {
            color: #0f5132;
            background: #d1e7dd;
        }
        .qr-card-badge.is-expired {
            color: #842029;
            background: #f8d7da;
        }
        .qr-result {
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding-top: 30px;
            text-align: left;
        }
        .qr-result-card {
            border: 1px solid #ececec;
            border-radius: 18px;
            background: #fff;
            padding: 26px;
            box-shadow: 0 16px 36px rgba(20, 23, 27, 0.08);
            max-width: 560px;
            margin: 0 auto;
        }
        .qr-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }
        .qr-card-title {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            color: #111;
        }
        .qr-card-badge {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f5132;
            background: #d1e7dd;
            padding: 7px 10px;
            border-radius: 999px;
            line-height: 1;
        }
        .qr-layout {
            display: grid;
            grid-template-columns: minmax(220px, 360px) minmax(180px, 1fr);
            gap: 18px;
            align-items: stretch;
            margin-bottom: 10px;
        }
        .qr-box {
            position: relative;
            max-width: 360px;
            margin: 0 auto;
            padding: 12px;
            border: 1px solid #ebebeb;
            border-radius: 12px;
            background: #fafafa;
        }
        .qr-box img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            display: block;
            transition: opacity 0.25s ease, filter 0.25s ease;
        }
        .qr-box.is-stale img {
            opacity: 0.22;
            filter: grayscale(1) blur(1.5px);
            pointer-events: none;
        }
        .qr-refresh-overlay {
            display: none;
            position: absolute;
            inset: 12px;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        .qr-box.is-stale .qr-refresh-overlay {
            display: flex;
        }
        .qr-refresh-btn {
            min-width: 148px;
            min-height: 48px;
        }
        .qr-side-meta {
            display: flex;
            flex-direction: column;
            gap: 14px;
            justify-content: center;
        }
        .countdown-wrap {
            border: 1px dashed #d9d9d9;
            border-radius: 12px;
            padding: 14px 16px;
            background: #fbfbfb;
        }
        .countdown-label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .countdown-value {
            font-size: 30px;
            line-height: 1;
            color: #111;
            letter-spacing: 0.02em;
        }
        .btn-copy:disabled {
            opacity: 0.45;
            pointer-events: none;
        }
        .copy-action-wrap {
            position: relative;
            width: 100%;
        }
        .copy-tooltip {
            position: absolute;
            left: 50%;
            bottom: calc(100% + 10px);
            transform: translateX(-50%) translateY(4px);
            background: #198754;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 999px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .copy-tooltip.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .qr-meta-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .qr-meta-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-top: 1px solid #f0f0f0;
            padding-top: 10px;
        }
        .meta-label {
            font-size: 14px;
            color: #666;
        }
        .meta-value {
            font-size: 15px;
            color: #111;
            letter-spacing: 0.02em;
        }
        #paymentMessage {
            min-height: 22px;
        }
        #paymentMessage.text-danger {
            color: #dc3545;
        }
        #paymentMessage.text-success {
            color: #198754;
        }
        @media screen and (max-width: 800px) {
            #MainContent {
                padding-top: 84px;
            }
            .text-only h1 {
                font-size: 50px;
            }
            .section-title {
                font-size: 32px;
            }
            .qr-result-card {
                padding: 18px;
                border-radius: 14px;
            }
            .qr-layout {
                grid-template-columns: 1fr;
            }
            .qr-side-meta {
                width: 100%;
            }
            .qr-card-top {
                flex-direction: column;
                align-items: flex-start;
            }
            .qr-card-title {
                font-size: 20px;
            }
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        .pay-success-dialog {
            border: 0;
            padding: 0;
            background: transparent;
            max-width: none;
            max-height: none;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
        .pay-success-dialog[open] {
            display: grid;
            place-items: center;
        }
        .pay-success-dialog::backdrop {
            background: rgba(16, 18, 22, 0.48);
            backdrop-filter: blur(6px);
        }
        .pay-success-confetti {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        .pay-success-card {
            position: relative;
            z-index: 2;
            width: min(92vw, 420px);
            padding: 22px 22px 24px;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 28px 64px rgba(16, 18, 22, 0.22);
            text-align: center;
        }
        .pay-success-kicker {
            margin: 0 0 14px;
            font-size: 18px;
            font-weight: 600;
            color: #1f6b4a;
        }
        .pay-success-rule {
            height: 1px;
            background: #ececec;
            margin: 0 0 28px;
        }
        .pay-success-mark {
            position: relative;
            width: 112px;
            height: 112px;
            margin: 0 auto 22px;
        }
        .pay-success-burst {
            position: absolute;
            inset: -18px;
            pointer-events: none;
        }
        .pay-success-burst span {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 8px;
            height: 8px;
            margin: -4px 0 0 -4px;
            border-radius: 2px;
            background: var(--dot, #f59e0b);
            transform: rotate(var(--rot, 0deg)) translateY(-52px);
            animation: payBurst 700ms cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .pay-success-check {
            position: relative;
            z-index: 1;
            width: 88px;
            height: 88px;
            margin: 12px auto 0;
            border-radius: 50%;
            background: #22c55e;
            color: #fff;
            display: grid;
            place-items: center;
            box-shadow: 0 14px 28px rgba(34, 197, 94, 0.32);
            animation: payPop 520ms cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        .pay-success-check svg {
            width: 42px;
            height: 42px;
        }
        .pay-success-title {
            margin: 0 0 16px;
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.35;
            text-wrap: balance;
        }
        .pay-success-code {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            box-sizing: border-box;
            min-height: 48px;
            padding: 10px 12px 10px 14px;
            margin: 0 0 10px;
            background: #f3f4f6;
            border-radius: 14px;
            text-align: left;
        }
        .pay-success-code span {
            flex: 1;
            min-width: 0;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            letter-spacing: 0.04em;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .pay-success-copy {
            flex: none;
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 10px;
            background: #e8eaed;
            color: #16a34a;
            display: grid;
            place-items: center;
            cursor: pointer;
        }
        .pay-success-copy:hover,
        .pay-success-copy:focus-visible {
            background: #dcefe3;
            outline: none;
        }
        .pay-success-copy svg {
            width: 18px;
            height: 18px;
        }
        .pay-success-hint {
            margin: 0 0 18px;
            font-size: 13px;
            color: #6b7280;
        }
        .pay-success-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .pay-success-primary {
            appearance: none;
            border: 0;
            border-radius: 999px;
            min-height: 48px;
            padding: 12px 18px;
            background: #16a34a;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .pay-success-primary:hover,
        .pay-success-primary:focus-visible {
            background: #15803d;
            outline: none;
        }
        .pay-success-ghost {
            appearance: none;
            border: 0;
            background: transparent;
            min-height: 40px;
            color: #6b7280;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }
        .pay-success-ghost:hover,
        .pay-success-ghost:focus-visible {
            color: #111;
            outline: none;
        }
        @keyframes payPop {
            from {
                transform: scale(0.6);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        @keyframes payBurst {
            from {
                opacity: 0;
                transform: rotate(var(--rot, 0deg)) translateY(-18px) scale(0.4);
            }
            to {
                opacity: 1;
                transform: rotate(var(--rot, 0deg)) translateY(-52px) scale(1);
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .pay-success-check,
            .pay-success-burst span {
                animation: none;
            }
        }
    </style>
    <div id="MainContent" tabindex="-1">
        <main data-header-color="dark">
            <div class="container text-only">
                <div class="payment-content">
                    <h1>Thanh toán</h1>
                    <div class="blurb">
                        <p>Tạo mã QR thanh toán nhanh trong một bước. Mỗi đơn có nội dung CK riêng và hiệu lực 12 phút. Chuyển khoản đúng số tiền, đúng nội dung — hệ thống tự xác nhận.</p>
                    </div>
                    <div class="payment-form-wrap">
                        <form id="paymentForm" action="#" method="POST">
                            <div class="form-group mb-4">
                                <label class="input-label" for="facebookLink">Link Facebook</label>
                                <input type="url" name="facebookLink" class="form-control" id="facebookLink" placeholder="https://facebook.com/..." required>
                                <p class="input-hint">Dùng để tạo nội dung chuyển khoản riêng cho đơn này.</p>
                            </div>
                            <div class="form-group mb-4">
                                <label class="input-label" for="customerEmail">Email (không bắt buộc)</label>
                                <input type="email" name="customerEmail" class="form-control" id="customerEmail" placeholder="Bạn sẽ nhận mail khi thanh toán thành công">
                            </div>
                            <button type="submit" class="btn-default btn-highlighted qr-submit-btn" id="generateQrBtn">
                                <span class="btn-text">Tạo mã QR</span>
                                <span class="btn-loader" id="generateQrLoader" aria-hidden="true"></span>
                            </button>
                            <div id="paymentMessage" class="mt-3"></div>
                        </form>
                    </div>
                    <div class="qr-result" id="qrResult" style="display:none;">
                        <h2 class="section-title">Thông tin thanh toán</h2>
                        <div class="qr-result-card">
                            <div class="qr-card-top">
                                <p class="qr-card-title">VietQR - TPBank</p>
                                <span class="qr-card-badge" id="qrStatusBadge">Đang hoạt động</span>
                            </div>
                            <div class="qr-layout">
                                <div class="qr-box" id="qrBox">
                                    <img id="vietQrImage" src="" alt="VietQR Payment">
                                    <div class="qr-refresh-overlay" id="qrRefreshOverlay">
                                        <button type="button" class="btn-default btn-highlighted qr-refresh-btn" id="refreshQrBtn">Làm mới</button>
                                    </div>
                                </div>
                                <div class="qr-side-meta">
                                    <div class="countdown-wrap">
                                        <span class="countdown-label">Còn hiệu lực</span>
                                        <strong class="countdown-value" id="expiryCountdownValue">12:00</strong>
                                    </div>
                                    <div class="copy-action-wrap">
                                        <button type="button" class="btn-default btn-copy" id="copyTransferCodeBtn">Sao chép nội dung CK</button>
                                        <span class="copy-tooltip" id="copySuccessTooltip">Đã sao chép</span>
                                    </div>
                                </div>
                            </div>
                            <div class="qr-meta-list">
                                <div class="qr-meta-item">
                                    <span class="meta-label">Số tiền</span>
                                    <strong class="meta-value" id="amountValue">150.000 VND</strong>
                                </div>
                                <div class="qr-meta-item">
                                    <span class="meta-label">Nội dung CK</span>
                                    <strong class="meta-value" id="transferCodeValue"></strong>
                                </div>
                                <div class="qr-meta-item">
                                    <span class="meta-label">Hết hạn lúc</span>
                                    <strong class="meta-value" id="expiresAtValue"></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <dialog id="paymentSuccessDialog" class="pay-success-dialog" aria-labelledby="paySuccessTitle">
        <canvas id="paySuccessConfetti" class="pay-success-confetti" aria-hidden="true"></canvas>
        <div class="pay-success-card">
            <p class="pay-success-kicker">Thành công</p>
            <div class="pay-success-rule"></div>
            <div class="pay-success-mark" aria-hidden="true">
                <span class="pay-success-burst">
                    <span style="--rot: 12deg; --dot: #f59e0b;"></span>
                    <span style="--rot: 48deg; --dot: #38bdf8;"></span>
                    <span style="--rot: 84deg; --dot: #f472b6;"></span>
                    <span style="--rot: 122deg; --dot: #22c55e;"></span>
                    <span style="--rot: 158deg; --dot: #a78bfa;"></span>
                    <span style="--rot: 198deg; --dot: #fb7185;"></span>
                    <span style="--rot: 236deg; --dot: #facc15;"></span>
                    <span style="--rot: 274deg; --dot: #2dd4bf;"></span>
                    <span style="--rot: 312deg; --dot: #60a5fa;"></span>
                    <span style="--rot: 348deg; --dot: #f97316;"></span>
                </span>
                <span class="pay-success-check">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12.5l4.2 4.2L19 7.5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
            <h2 class="pay-success-title" id="paySuccessTitle">Thanh toán thành công</h2>
            <div class="pay-success-code">
                <span id="paySuccessCode"></span>
                <button type="button" class="pay-success-copy" id="paySuccessCopyBtn" aria-label="Sao chép mã đơn">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="8" y="8" width="11" height="11" rx="2" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M6 16H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            <p class="pay-success-hint">Đơn đã được xác nhận. Bạn có thể tạo QR khác khi cần.</p>
            <div class="pay-success-actions">
                <button type="button" class="pay-success-primary" id="paySuccessRefreshBtn">Làm mới QR</button>
                <button type="button" class="pay-success-ghost" id="paySuccessCloseBtn">Đóng</button>
            </div>
        </div>
    </dialog>
    @if (file_exists(public_path('hot')) || file_exists(public_path('build/manifest.json')))
        @vite(['resources/js/app.js'])
    @endif
    <script>
        (function () {
            var paymentForm = document.getElementById("paymentForm");
            var facebookInput = document.getElementById("facebookLink");
            var customerEmailInput = document.getElementById("customerEmail");
            var paymentMessage = document.getElementById("paymentMessage");
            var qrResult = document.getElementById("qrResult");
            var qrImage = document.getElementById("vietQrImage");
            var transferCodeValue = document.getElementById("transferCodeValue");
            var expiresAtValue = document.getElementById("expiresAtValue");
            var amountValue = document.getElementById("amountValue");
            var qrStatusBadge = document.getElementById("qrStatusBadge");
            var qrBox = document.getElementById("qrBox");
            var refreshQrBtn = document.getElementById("refreshQrBtn");
            var generateQrBtn = document.getElementById("generateQrBtn");
            var pageLoadingOverlay = document.getElementById("pageLoadingOverlay");
            var copyTransferCodeBtn = document.getElementById("copyTransferCodeBtn");
            var copySuccessTooltip = document.getElementById("copySuccessTooltip");
            var expiryCountdownValue = document.getElementById("expiryCountdownValue");
            var paymentSuccessDialog = document.getElementById("paymentSuccessDialog");
            var paySuccessCode = document.getElementById("paySuccessCode");
            var paySuccessCopyBtn = document.getElementById("paySuccessCopyBtn");
            var paySuccessRefreshBtn = document.getElementById("paySuccessRefreshBtn");
            var paySuccessCloseBtn = document.getElementById("paySuccessCloseBtn");
            var paySuccessConfetti = document.getElementById("paySuccessConfetti");
            var pollTimer = null;
            var countdownTimer = null;
            var copyTooltipTimer = null;
            var echoWaitTimer = null;
            var latestTransferCode = "";
            var currentExpiresAt = null;
            var orderSettled = false;
            var echoChannel = null;
            var creatingOrder = false;
            var confettiRaf = 0;

            function stopCelebration() {
                if (confettiRaf) {
                    cancelAnimationFrame(confettiRaf);
                    confettiRaf = 0;
                }
                if (paySuccessConfetti) {
                    var ctx = paySuccessConfetti.getContext("2d");
                    if (ctx) {
                        ctx.clearRect(0, 0, paySuccessConfetti.width, paySuccessConfetti.height);
                    }
                }
            }

            function launchCelebration() {
                stopCelebration();
                if (!paySuccessConfetti || !paySuccessConfetti.getContext) {
                    return;
                }
                if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
                    return;
                }
                var canvas = paySuccessConfetti;
                var ctx = canvas.getContext("2d");
                var dpr = Math.min(window.devicePixelRatio || 1, 2);
                var width = window.innerWidth;
                var height = window.innerHeight;
                canvas.width = Math.floor(width * dpr);
                canvas.height = Math.floor(height * dpr);
                canvas.style.width = width + "px";
                canvas.style.height = height + "px";
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                var colors = ["#22c55e", "#f59e0b", "#f472b6", "#38bdf8", "#a78bfa", "#fb7185", "#facc15", "#2dd4bf"];
                var originX = width / 2;
                var originY = Math.max(140, height / 2 - 90);
                var pieces = [];
                var i;
                for (i = 0; i < 150; i += 1) {
                    var angle = ((Math.PI * 2) * i) / 150 + Math.random() * 0.25;
                    var speed = 3.4 + Math.random() * 8.5;
                    pieces.push({
                        x: originX,
                        y: originY,
                        vx: Math.cos(angle) * speed,
                        vy: Math.sin(angle) * speed - 3.2,
                        g: 0.12 + Math.random() * 0.08,
                        w: 5 + Math.random() * 7,
                        h: 7 + Math.random() * 8,
                        rot: Math.random() * Math.PI,
                        vr: (Math.random() - 0.5) * 0.28,
                        color: colors[i % colors.length],
                        shape: i % 3
                    });
                }
                var start = performance.now();
                function frame(now) {
                    var elapsed = (now - start) / 1000;
                    ctx.clearRect(0, 0, width, height);
                    var alive = false;
                    for (i = 0; i < pieces.length; i += 1) {
                        var p = pieces[i];
                        p.vy += p.g;
                        p.x += p.vx;
                        p.y += p.vy;
                        p.vx *= 0.991;
                        p.rot += p.vr;
                        var life = Math.max(0, 1 - elapsed / 2.15);
                        if (life <= 0 || p.y > height + 40) {
                            continue;
                        }
                        alive = true;
                        ctx.save();
                        ctx.translate(p.x, p.y);
                        ctx.rotate(p.rot);
                        ctx.globalAlpha = life;
                        ctx.fillStyle = p.color;
                        if (p.shape === 0) {
                            ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                        } else if (p.shape === 1) {
                            ctx.beginPath();
                            ctx.arc(0, 0, p.w / 2.2, 0, Math.PI * 2);
                            ctx.fill();
                        } else {
                            ctx.beginPath();
                            ctx.moveTo(0, -p.h / 2);
                            ctx.lineTo(p.w / 2, p.h / 2);
                            ctx.lineTo(-p.w / 2, p.h / 2);
                            ctx.closePath();
                            ctx.fill();
                        }
                        ctx.restore();
                    }
                    if (alive && elapsed < 2.3) {
                        confettiRaf = requestAnimationFrame(frame);
                    } else {
                        ctx.clearRect(0, 0, width, height);
                        confettiRaf = 0;
                    }
                }
                confettiRaf = requestAnimationFrame(frame);
            }

            function closeSuccessModal() {
                stopCelebration();
                if (paymentSuccessDialog && paymentSuccessDialog.open) {
                    paymentSuccessDialog.close();
                }
            }

            function showSuccessModal(code) {
                if (!paymentSuccessDialog) {
                    return;
                }
                paySuccessCode.textContent = code || latestTransferCode;
                if (!paymentSuccessDialog.open) {
                    paymentSuccessDialog.showModal();
                }
                launchCelebration();
                paySuccessRefreshBtn.focus();
            }

            function pad(num) {
                return num < 10 ? "0" + num : String(num);
            }

            function formatDateTime(date) {
                return pad(date.getHours()) + ":" + pad(date.getMinutes()) + ":" + pad(date.getSeconds()) + " " + pad(date.getDate()) + "/" + pad(date.getMonth() + 1) + "/" + date.getFullYear();
            }

            function formatAmount(amount) {
                return Number(amount).toLocaleString("vi-VN") + " VND";
            }

            function setMessage(text, isError) {
                paymentMessage.className = isError ? "text-danger" : "text-success";
                paymentMessage.textContent = text;
            }

            function setSubmitLoading(isLoading) {
                if (isLoading) {
                    generateQrBtn.classList.add("is-loading");
                    generateQrBtn.setAttribute("disabled", "disabled");
                    pageLoadingOverlay.style.display = "flex";
                    document.body.style.overflow = "hidden";
                } else {
                    generateQrBtn.classList.remove("is-loading");
                    generateQrBtn.removeAttribute("disabled");
                    pageLoadingOverlay.style.display = "none";
                    document.body.style.overflow = "";
                }
            }

            function showCopyTooltip() {
                if (copyTooltipTimer) {
                    clearTimeout(copyTooltipTimer);
                }
                copySuccessTooltip.classList.add("show");
                copyTooltipTimer = setTimeout(function () {
                    copySuccessTooltip.classList.remove("show");
                }, 1400);
            }

            function formatCountdown(ms) {
                if (ms <= 0) {
                    return "00:00";
                }
                var totalSeconds = Math.floor(ms / 1000);
                var minutes = Math.floor(totalSeconds / 60);
                var seconds = totalSeconds % 60;
                return pad(minutes) + ":" + pad(seconds);
            }

            function stopWatchers() {
                if (pollTimer) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                }
                if (countdownTimer) {
                    clearInterval(countdownTimer);
                    countdownTimer = null;
                }
                if (echoWaitTimer) {
                    clearInterval(echoWaitTimer);
                    echoWaitTimer = null;
                }
                if (echoChannel && window.Echo) {
                    window.Echo.leave("order." + latestTransferCode);
                    echoChannel = null;
                }
            }

            function settleQr(kind) {
                if (orderSettled) {
                    return;
                }
                orderSettled = true;
                stopWatchers();
                qrBox.classList.add("is-stale");
                copyTransferCodeBtn.setAttribute("disabled", "disabled");
                expiryCountdownValue.textContent = "00:00";
                qrStatusBadge.classList.remove("is-paid", "is-expired");
                if (kind === "paid") {
                    qrStatusBadge.classList.add("is-paid");
                    qrStatusBadge.textContent = "Đã thanh toán";
                    paymentMessage.className = "";
                    paymentMessage.textContent = "";
                    showSuccessModal(latestTransferCode);
                } else {
                    qrStatusBadge.classList.add("is-expired");
                    qrStatusBadge.textContent = "Hết hạn";
                    setMessage("QR đã hết hạn. Bấm Làm mới để tạo mã mới.", true);
                }
            }

            function markPaid() {
                settleQr("paid");
            }

            function markExpired() {
                settleQr("expired");
            }

            function applyStatus(status) {
                if (status === "paid") {
                    markPaid();
                } else if (status === "expired") {
                    markExpired();
                }
            }

            function startCountdown() {
                if (countdownTimer) {
                    clearInterval(countdownTimer);
                }
                countdownTimer = setInterval(function () {
                    if (!currentExpiresAt || orderSettled) {
                        return;
                    }
                    var remainingMs = currentExpiresAt.getTime() - Date.now();
                    expiryCountdownValue.textContent = formatCountdown(remainingMs);
                    if (remainingMs <= 0) {
                        markExpired();
                    }
                }, 1000);
            }

            function startStatusPoll(orderCode) {
                if (pollTimer) {
                    clearInterval(pollTimer);
                }
                pollTimer = setInterval(async function () {
                    if (orderSettled) {
                        return;
                    }
                    try {
                        var response = await fetch("/api/v1/orders/" + encodeURIComponent(orderCode), {
                            headers: { "Accept": "application/json" }
                        });
                        if (!response.ok) {
                            return;
                        }
                        var payload = await response.json();
                        applyStatus(payload && payload.data ? payload.data.status : null);
                    } catch (error) {}
                }, 4000);
            }

            function subscribeEcho(orderCode) {
                if (echoWaitTimer) {
                    clearInterval(echoWaitTimer);
                }
                var tries = 0;
                echoWaitTimer = setInterval(function () {
                    tries += 1;
                    if (!window.Echo) {
                        if (tries > 40) {
                            clearInterval(echoWaitTimer);
                            echoWaitTimer = null;
                        }
                        return;
                    }
                    clearInterval(echoWaitTimer);
                    echoWaitTimer = null;
                    echoChannel = window.Echo.private("order." + orderCode);
                    echoChannel.listen(".payment.success", markPaid);
                    echoChannel.listen(".payment.expired", markExpired);
                }, 250);
            }

            function renderOrder(data) {
                orderSettled = false;
                closeSuccessModal();
                latestTransferCode = data.transfer_content || data.order_code;
                currentExpiresAt = new Date(data.expires_at);
                qrBox.classList.remove("is-stale");
                copyTransferCodeBtn.removeAttribute("disabled");
                qrStatusBadge.classList.remove("is-paid", "is-expired");
                qrImage.src = data.qr_image_url;
                transferCodeValue.textContent = latestTransferCode;
                amountValue.textContent = formatAmount(data.amount);
                expiresAtValue.textContent = formatDateTime(currentExpiresAt);
                expiryCountdownValue.textContent = formatCountdown(currentExpiresAt.getTime() - Date.now());
                qrStatusBadge.textContent = "Đang chờ CK";
                qrResult.style.display = "block";
                paymentMessage.className = "";
                paymentMessage.textContent = "";
                startCountdown();
                startStatusPoll(data.order_code);
                subscribeEcho(data.order_code);
            }

            async function createOrder() {
                var facebookLink = facebookInput.value.trim();
                var email = customerEmailInput.value.trim();
                if (!facebookLink) {
                    setMessage("Vui lòng nhập link Facebook.", true);
                    return false;
                }
                if (!/^https?:\/\/(www\.)?facebook\.com\//i.test(facebookLink)) {
                    setMessage("Link phải là địa chỉ facebook.com.", true);
                    return false;
                }
                if (creatingOrder) {
                    return false;
                }
                creatingOrder = true;
                try {
                    stopWatchers();
                    setSubmitLoading(true);
                    var body = { facebook_profile_link: facebookLink };
                    if (email) {
                        body.email = email;
                    }
                    var response = await fetch("/api/v1/orders", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify(body)
                    });
                    var payload = await response.json();
                    if (!response.ok || !payload.status || !payload.data) {
                        setMessage((payload && payload.message) || "Không thể tạo mã QR lúc này. Vui lòng thử lại.", true);
                        return false;
                    }
                    renderOrder(payload.data);
                    return true;
                } catch (error) {
                    setMessage("Không thể tạo mã QR lúc này. Vui lòng thử lại.", true);
                    return false;
                } finally {
                    creatingOrder = false;
                    setSubmitLoading(false);
                }
            }

            paymentForm.addEventListener("submit", async function (event) {
                event.preventDefault();
                var ok = await createOrder();
                if (!ok && !latestTransferCode) {
                    qrResult.style.display = "none";
                }
            });

            refreshQrBtn.addEventListener("click", async function () {
                closeSuccessModal();
                await createOrder();
            });

            paySuccessRefreshBtn.addEventListener("click", async function () {
                closeSuccessModal();
                await createOrder();
            });

            paySuccessCloseBtn.addEventListener("click", function () {
                closeSuccessModal();
            });

            paySuccessCopyBtn.addEventListener("click", async function () {
                var code = paySuccessCode.textContent;
                if (!code) {
                    return;
                }
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(code);
                    } else {
                        var hiddenInput = document.createElement("input");
                        hiddenInput.value = code;
                        document.body.appendChild(hiddenInput);
                        hiddenInput.select();
                        document.execCommand("copy");
                        document.body.removeChild(hiddenInput);
                    }
                    showCopyTooltip();
                } catch (error) {}
            });

            paymentSuccessDialog.addEventListener("click", function (event) {
                if (event.target === paymentSuccessDialog) {
                    closeSuccessModal();
                }
            });

            paymentSuccessDialog.addEventListener("close", function () {
                stopCelebration();
            });

            copyTransferCodeBtn.addEventListener("click", async function () {
                if (orderSettled || !latestTransferCode) {
                    setMessage("QR này không còn dùng được. Bấm Làm mới để tạo mã mới.", true);
                    return;
                }
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(latestTransferCode);
                    } else {
                        var hiddenInput = document.createElement("input");
                        hiddenInput.value = latestTransferCode;
                        document.body.appendChild(hiddenInput);
                        hiddenInput.select();
                        document.execCommand("copy");
                        document.body.removeChild(hiddenInput);
                    }
                    paymentMessage.className = "";
                    paymentMessage.textContent = "";
                    showCopyTooltip();
                } catch (error) {
                    setMessage("Không thể sao chép. Vui lòng sao chép thủ công.", true);
                }
            });
        })();
    </script>
@endsection
