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
        .btn-copy {
            width: 100%;
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
                                <div class="qr-box">
                                    <img id="vietQrImage" src="" alt="VietQR Payment">
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
            var generateQrBtn = document.getElementById("generateQrBtn");
            var pageLoadingOverlay = document.getElementById("pageLoadingOverlay");
            var copyTransferCodeBtn = document.getElementById("copyTransferCodeBtn");
            var copySuccessTooltip = document.getElementById("copySuccessTooltip");
            var expiryCountdownValue = document.getElementById("expiryCountdownValue");
            var pollTimer = null;
            var countdownTimer = null;
            var copyTooltipTimer = null;
            var echoWaitTimer = null;
            var latestTransferCode = "";
            var currentExpiresAt = null;
            var orderSettled = false;
            var echoChannel = null;

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

            function markPaid() {
                if (orderSettled) {
                    return;
                }
                orderSettled = true;
                stopWatchers();
                qrStatusBadge.textContent = "Đã thanh toán";
                expiryCountdownValue.textContent = "00:00";
                setMessage("Thanh toán thành công. Cảm ơn bạn.", false);
            }

            function markExpired() {
                if (orderSettled) {
                    return;
                }
                orderSettled = true;
                stopWatchers();
                qrStatusBadge.textContent = "Hết hạn";
                expiryCountdownValue.textContent = "00:00";
                setMessage("Đơn đã hết hạn. Tạo mã QR mới nếu bạn vẫn muốn thanh toán.", true);
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
                        qrStatusBadge.textContent = "Hết hạn";
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
                latestTransferCode = data.transfer_content || data.order_code;
                currentExpiresAt = new Date(data.expires_at);
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

            paymentForm.addEventListener("submit", async function (event) {
                event.preventDefault();
                var facebookLink = facebookInput.value.trim();
                var email = customerEmailInput.value.trim();
                if (!facebookLink) {
                    setMessage("Vui lòng nhập link Facebook.", true);
                    qrResult.style.display = "none";
                    return;
                }
                if (!/^https?:\/\/(www\.)?facebook\.com\//i.test(facebookLink)) {
                    setMessage("Link phải là địa chỉ facebook.com.", true);
                    qrResult.style.display = "none";
                    return;
                }
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
                        qrResult.style.display = "none";
                        return;
                    }
                    renderOrder(payload.data);
                } catch (error) {
                    setMessage("Không thể tạo mã QR lúc này. Vui lòng thử lại.", true);
                    qrResult.style.display = "none";
                } finally {
                    setSubmitLoading(false);
                }
            });

            copyTransferCodeBtn.addEventListener("click", async function () {
                if (!latestTransferCode) {
                    setMessage("Chưa có nội dung chuyển khoản để sao chép.", true);
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
