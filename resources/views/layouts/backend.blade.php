<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earthlonis — Admin Panel</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="bg-dark p-3 d-flex flex-column" style="width: 230px; min-height: 100vh;">

        <div class="d-flex align-items-center gap-2 mb-1">
            @include('partials.logo-icon', ['size' => 26])
            <span class="text-white fw-bold" style="font-size:17px;">Earthlonis</span>
        </div>
        <div class="text-secondary mb-4" style="font-size:11px; text-transform:uppercase; letter-spacing:0.06em;">Admin Panel</div>

        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('backend.dashboard') ? 'active text-white bg-secondary rounded' : 'text-secondary' }}"
                   href="{{ route('backend.dashboard') }}">
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('backend.countries.*') ? 'active text-white bg-secondary rounded' : 'text-secondary' }}"
                   href="{{ route('backend.countries.index') }}">
                    Countries
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('backend.categories.*') ? 'active text-white bg-secondary rounded' : 'text-secondary' }}"
                   href="{{ route('backend.categories.index') }}">
                    Categories
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('backend.places.*') ? 'active text-white bg-secondary rounded' : 'text-secondary' }}"
                   href="{{ route('backend.places.index') }}">
                    Places
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('backend.comments.*') ? 'active text-white bg-secondary rounded' : 'text-secondary' }}"
                href="{{ route('backend.comments.index') }}">
                    Comments
                </a>
            </li>

            <hr class="text-secondary">

            <li class="nav-item">
                <a class="nav-link text-secondary" href="/">← View site</a>
            </li>

        </ul>

    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1">

        <!-- TOPBAR -->
        <nav class="navbar navbar-dark bg-dark px-4">
            <div class="ms-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </nav>

        <!-- PAGE CONTENT -->
        <div class="p-4">
            <!-- FLASH MESSAGES -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.tiny.cloud/1/ngviz73ikymg6wxewyeddt971rym7zd8xqoagoqvqv08ltqf/tinymce/6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 400,
        plugins: 'link image lists table code',
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
        menubar: false
    });
</script>

</body>
</html>