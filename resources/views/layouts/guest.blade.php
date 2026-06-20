<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Earthlonis') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5" style="background:#eaf4fb;">

    <div style="width:100%; max-width:420px;">

        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="text-decoration-none d-inline-flex align-items-center gap-2" style="font-family: system-ui, sans-serif; font-size: 26px; font-weight: 700;">
                @include('partials.logo-icon', ['size' => 34])
                <span><span style="color:#2176ae;">Earth</span><span style="color:#1a4a6e;">lonis</span></span>
            </a>
        </div>

        <div class="card shadow-sm border-0 p-4">
            {{ $slot }}
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
