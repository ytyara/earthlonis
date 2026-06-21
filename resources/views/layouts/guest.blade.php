<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Earthlonis') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5 bg-light">

    <div class="mw-auth">

        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 fs-3 fw-bold">
                @include('partials.logo-icon', ['size' => 34])
                <span><span class="text-primary">Earth</span><span class="text-brand-deep">lonis</span></span>
            </a>
        </div>

        <div class="card shadow-sm border-0 p-4">
            {{ $slot }}
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
