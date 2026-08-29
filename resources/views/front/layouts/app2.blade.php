<!DOCTYPE html>
<html lang="en" data-x="html" data-x-toggle="html-overflow-hidden">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Analytics: the gtag() queue is defined here so any early call still
         records, but the network requests are started after load (see the
         bottom of <body>). This keeps third-party JS off the critical path
         without losing a single event. --}}
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-9M525H2VB2');
    </script>

    <!-- Preconnect to critical origins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Google fonts (with font-display swap) -->
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    {{-- Bootstrap Icons: decorative only (12 uses site-wide), so it is loaded
         non-render-blocking. The preload+onload swap lets the browser fetch it at
         high priority without holding up first paint; <noscript> keeps it working
         with JS disabled. --}}
    <link rel="preload" as="style"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </noscript>

    <!-- Critical layout CSS: loaded render-blocking, on purpose, so the page never paints unstyled -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}">

    {{-- SEO Meta Data --}}
    <title>@yield('title', 'Authentic Morocco Adventures | Explore Morocco with a Local Tour Guide')</title>

    <meta name="description" content="@yield('meta_description', 'Discover authentic Morocco tours with Authentic Morocco Adventures. From desert adventures to cultural excursions, explore Morocco with a local expert guide.')">

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Optional keywords --}}
    <meta name="keywords" content="@yield('meta_keywords', 'Morocco tours, Marrakech tours, desert tours, local tour guide Morocco, private Morocco tours')">

    <meta name="robots" content="@yield('meta_robots', 'index, follow')">

    <meta name="author" content="Authentic Morocco Adventures">

    <meta http-equiv="Content-Language" content="en">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'Authentic Morocco Adventures | Explore Morocco with a Local Tour Guide')">
    <meta property="og:description" content="@yield('og_description', 'Discover authentic Morocco tours with Authentic Morocco Adventures. From desert adventures to cultural excursions, explore Morocco with a local expert guide.')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/home/marrakech-souk-moroccan-brass-lanterns-market.webp'))">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Golden Moroccan brass lanterns glowing in the vibrant souks of Marrakech.')">
    <meta property="og:site_name" content="Authentic Morocco Adventures">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Authentic Morocco Adventures | Explore Morocco with a Local Tour Guide')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Discover authentic Morocco tours with Authentic Morocco Adventures. From desert adventures to cultural excursions, explore Morocco with a local expert guide.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('assets/images/home/marrakech-souk-moroccan-brass-lanterns-market.webp'))">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/images/favicon/favicon-96x96.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TourGuide",
  "name": "Authentic Morocco Adventures",
  "image": "https://www.authenticmoroccoadventures.com/assets/images/home/marrakech-souk-moroccan-brass-lanterns-market.webp",
  "description": "Authentic Morocco Adventures is a professional tour guide company offering private and authentic experiences across Morocco. Explore Marrakech, the Sahara Desert, and cultural gems with a local expert.",
  "url": "https://www.authenticmoroccoadventures.com/",
  "sameAs": [
    "https://web.facebook.com/authenticmoroccoadventures/",
    "https://www.instagram.com/authenticmoroccoadventures/",
    "https://x.com/AMADMCmor",
    "https://fr.pinterest.com/amoroccoadventures/",
    "https://www.youtube.com/@AuthenticMoroccoAdventures",
    "https://www.linkedin.com/in/authentic-moroccoadventures-99812a420/",
    "https://fr.trustpilot.com/review/authenticmoroccoadventures.com"
  ],
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "MA",
    "addressLocality": "Marrakech",
    "addressRegion": "Marrakech-Safi"
  },
  "telephone": "+212666107312",
  "priceRange": "$$",
  "founder": {
    "@type": "Person",
    "name": "Mohammed",
    "jobTitle": "Tour Guide"
  }
}
</script>

    <style>
        .whatsapp-float {
            position: fixed;
            right: 30px;
            bottom: 30px;
            z-index: 100;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .whatsapp-float img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 50%;
        }

        /* Attention message that slides in from the left of the WhatsApp button,
           pauses, hides, and comes back on a loop. */
        .whatsapp-float__bubble {
            position: absolute;
            right: calc(100% + 12px);
            top: 50%;
            white-space: nowrap;
            background: #fff;
            color: #05073C;
            font-size: 14px;
            font-weight: 600;
            line-height: 1;
            padding: 10px 14px;
            border-radius: 22px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.16);
            pointer-events: none;
            transform: translate(20px, -50%);
            opacity: 0;
            animation: whatsappBubble 6s ease-in-out infinite;
        }

        .whatsapp-float__bubble::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 100%;
            transform: translateY(-50%);
            border: 7px solid transparent;
            border-left-color: #fff;
        }

        @keyframes whatsappBubble {
            0%   { transform: translate(20px, -50%); opacity: 0; }
            10%  { transform: translate(0, -50%);    opacity: 1; }
            45%  { transform: translate(0, -50%);    opacity: 1; }
            55%  { transform: translate(20px, -50%); opacity: 0; }
            100% { transform: translate(20px, -50%); opacity: 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            .whatsapp-float__bubble {
                animation: none;
                transform: translate(0, -50%);
                opacity: 1;
            }
        }

        @media (max-width: 767px) {
            .whatsapp-float {
                bottom: 20px;
                right: 20px;
                width: 54px;
                height: 54px;
            }

            .whatsapp-float__bubble {
                font-size: 12px;
                padding: 8px 11px;
            }
        }
    </style>

    {{-- Page-level styles pushed by child views --}}
    @stack('styles')

    {{-- Page-level JSON-LD pushed by child views (see tours/activities/trekking details) --}}
    @stack('schema')
</head>

<body>
<div class="tourPagesSidebar" data-x="tourPagesSidebar" data-x-toggle="-is-active">
    <div class="tourPagesSidebar__overlay" aria-hidden="true"></div>
    <div class="tourPagesSidebar__content">
        <div class="tourPagesSidebar__header d-flex items-center justify-between">
            <div class="text-20 fw-500">All filters</div>

            <button class="button -dark-1 size-40 rounded-full bg-light-1" data-x-click="tourPagesSidebar"
                    aria-label="Close filters panel">
                <i class="icon-cross text-10" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>

<button class="toTopButton js-top-button" aria-label="Scroll back to top">
    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"
         aria-hidden="true">
        <g clip-path="url(#clip0_83_4004)">
            <path
                d="M17.8783 0H4.12177C3.59388 0 3.16602 0.42786 3.16602 0.955755C3.16602 1.48365 3.59388 1.91151 4.12177 1.91151H17.8783C18.4062 1.91151 18.834 1.48365 18.834 0.955755C18.834 0.42786 18.4062 0 17.8783 0Z"/>
            <path
                d="M11.6759 4.67546C11.3026 4.30219 10.6975 4.30219 10.3242 4.67546L6.04107 8.95863C5.66779 9.3319 5.66779 9.937 6.04107 10.3103C6.41434 10.6837 7.01955 10.6836 7.39272 10.3103L10.0444 7.6587V21.0443C10.0444 21.5722 10.4723 22 11.0002 22C11.5281 22 11.9559 21.5722 11.9559 21.0443V7.65859L14.6076 10.3102C14.7942 10.4969 15.0389 10.5901 15.2834 10.5901C15.528 10.5901 15.7726 10.4968 15.9593 10.3102C16.3325 9.9369 16.3325 9.3318 15.9593 8.95852L11.6759 4.67546Z"/>
        </g>
        <defs>
            <clipPath id="clip0_83_4004">
                <rect width="22" height="22" fill="white"/>
            </clipPath>
        </defs>
    </svg>
</button>

<main>
    @include('front.partials._header2')

    @yield('content')

    @include('front.partials._footer2')
</main>

<a href="https://wa.me/212666107312?text=Hello%20Authentic%20Morocco%20Adventures%2C%20I%E2%80%99d%20like%20more%20info!"
   class="whatsapp-float" target="_blank" aria-label="Chat on WhatsApp">
    <span class="whatsapp-float__bubble" aria-hidden="true">Need help? Chat with us 👋</span>
    <img src="{{ asset('assets/images/icon/whatsapp.png') }}" alt="" aria-hidden="true" width="64" height="64" loading="lazy" decoding="async">
</a>

@include('front.partials._wishlist')

{{-- JS with minified versions --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
<script src="{{ asset('assets/js/vendors.min.js') }}" defer></script>
<script src="{{ asset('assets/js/main.min.js') }}" defer></script>
<script src="{{ asset('assets/js/favorites.js') }}" defer></script>
<script src="{{ asset('assets/js/protect.js') }}" defer></script>
<script src="{{ asset('assets/js/slider-fix.js') }}" defer></script>
<script src="{{ asset('assets/js/slider-autoscroll.js') }}" defer></script>
<script src="{{ asset('assets/js/mobile-css-slider-autoscroll.js') }}" defer></script>

@include('front.partials._recaptcha')

@stack('scripts')

{{-- Third-party analytics, loaded once the page has painted so they never
     compete with the LCP image or the theme JS for bandwidth. --}}
<script>
    (function () {
        function loadAnalytics() {
            var ga = document.createElement('script');
            ga.src = 'https://www.googletagmanager.com/gtag/js?id=G-9M525H2VB2';
            ga.async = true;
            document.head.appendChild(ga);

            var ah = document.createElement('script');
            ah.src = 'https://analytics.ahrefs.com/analytics.js';
            ah.setAttribute('data-key', 'TCYTyxlxLWFvUbmQZwxlhg');
            ah.async = true;
            document.head.appendChild(ah);
        }

        if (document.readyState === 'complete') {
            loadAnalytics();
        } else {
            window.addEventListener('load', loadAnalytics);
        }
    })();
</script>
</body>
</html>
