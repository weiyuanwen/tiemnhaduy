<!-- Jquery Library File -->
<script src="{{ asset('rosta/js/jquery-3.7.1.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/validator.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/jquery.slicknav.js') }}" defer></script>
<script src="{{ asset('rosta/js/swiper-bundle.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/jquery.waypoints.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/jquery.counterup.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/isotope.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/jquery.magnific-popup.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/SmoothScroll.js') }}" defer></script>
<script src="{{ asset('rosta/js/parallaxie.js') }}" defer></script>
<script src="{{ asset('rosta/js/gsap.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/magiccursor.js') }}" defer></script>
<script src="{{ asset('rosta/js/SplitText.js') }}" defer></script>
<script src="{{ asset('rosta/js/ScrollTrigger.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/wow.min.js') }}" defer></script>
<script src="{{ asset('rosta/js/function.js') }}" defer></script>
<script>
    (function () {
        var hidePreloader = function () {
            var preloader = document.querySelector('.preloader');
            if (!preloader) {
                return;
            }
            preloader.style.opacity = '0';
            preloader.style.pointerEvents = 'none';
            setTimeout(function () {
                preloader.style.display = 'none';
            }, 220);
        };
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', hidePreloader, { once: true });
        } else {
            hidePreloader();
        }
        window.addEventListener('load', hidePreloader, { once: true });
    })();
</script>
<script>
    (function () {
        window.dataLayer = window.dataLayer || [];
        window.gtag = window.gtag || function () { dataLayer.push(arguments); };
        var loadGtag = function () {
            if (document.querySelector('script[data-gtag]')) {
                return;
            }
            var script = document.createElement('script');
            script.async = true;
            script.dataset.gtag = '1';
            script.src = 'https://www.googletagmanager.com/gtag/js?id=G-4GNZ75JE64';
            script.onload = function () {
                window.gtag('js', new Date());
                window.gtag('config', 'G-4GNZ75JE64');
            };
            document.head.appendChild(script);
        };
        if ('requestIdleCallback' in window) {
            requestIdleCallback(loadGtag, { timeout: 4000 });
        } else {
            window.addEventListener('load', loadGtag, { once: true });
        }
    })();
</script>