<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Earthlonis') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f8fb;">

    {{-- HEADER --}}
    <header class="bg-white border-bottom">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="text-decoration-none d-inline-flex align-items-center gap-2" style="font-family: system-ui, sans-serif; font-size: 22px; font-weight: 700;">
                @include('partials.logo-icon', ['size' => 30])
                <span><span style="color:#2176ae;">Earth</span><span style="color:#1a4a6e;">lonis</span></span>
            </a>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('profile.edit') }}" class="text-decoration-none small" style="color:#4a6a80;">
                    {{ auth()->user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    {{-- PAGE HEADER --}}
    @isset($header)
        <div class="bg-white border-bottom py-3">
            <div class="container">
                {{ $header }}
            </div>
        </div>
    @endisset

    {{-- CONTENT --}}
    <main class="container py-5">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
