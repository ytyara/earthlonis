@extends('layouts.front')

@section('title', 'Page not found — Earthlonis')

@section('content')

<div class="container py-5 text-center">

    <div class="py-5">
        <div class="display-1 fw-bold text-light lh-1">404</div>

        <h1 class="mb-3 fs-4 fw-medium text-dark">
            Page not found
        </h1>

        <p class="mb-5 mx-auto text-secondary mw-auth">
            The page you are looking for doesn't exist or has been moved.
        </p>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ url('/') }}" class="btn btn-primary px-4">
                Go home
            </a>
            <a href="{{ route('places.index') }}" class="btn btn-outline-secondary px-4">
                Browse places
            </a>
        </div>
    </div>

</div>

@endsection