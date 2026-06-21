<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Earthlonis')</title>
    <meta name="description" content="@yield('description', 'A curated travel catalog of the most beautiful and fascinating destinations across the globe.')">
    <meta property="og:title" content="@yield('og_title', 'Earthlonis')">
    <meta property="og:description" content="@yield('og_description', 'A curated travel catalog of the most beautiful and fascinating destinations across the globe.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('img/og-default.jpg'))">
    <meta property="og:site_name" content="Earthlonis">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    @unless(config('app.allow_indexing'))
    <meta name="robots" content="noindex, nofollow">
    @endunless

    @stack('styles')
    @stack('structured_data')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        /* Keep every GLightbox slide the same size so photos don't jump around when navigating */
        .glightbox-clean .gslide-media {
            width: 85vw !important;
            height: 85vh !important;
            max-width: 1100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent !important;
            box-shadow: none !important;
        }
        .glightbox-clean .gslide-image img {
            width: auto !important;
            height: auto !important;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            box-shadow: none !important;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-page">

    {{-- HEADER --}}
    <header class="bg-white border-bottom">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="text-decoration-none px-2 d-inline-flex align-items-center gap-2 fs-4 fw-bold">
                @include('partials.logo-icon', ['size' => 30])
                <span><span class="text-primary">Earth</span><span class="text-brand-deep">lonis</span></span>
            </a>
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-top py-4">
        <div class="container text-center text-muted small">
            © {{ date('Y') }} Earthlonis — discover the world, one place at a time
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            GLightbox({ selector: '.glightbox' });
        });
    </script>

    @stack('scripts')
    
</body>
</html>