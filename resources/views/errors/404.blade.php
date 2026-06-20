@extends('layouts.front')

@section('title', 'Page not found — Earthlonis')

@section('content')

<div class="container py-5 text-center">

    <div class="py-5">
        <div style="font-size:96px; font-weight:700; color:#eaf4fb; line-height:1;">404</div>

        <h1 class="mb-3" style="font-size:24px; font-weight:500; color:#0d2a3f;">
            Page not found
        </h1>

        <p class="mb-5 mx-auto" style="font-size:15px; color:#4a6a80; max-width:400px;">
            The page you are looking for doesn't exist or has been moved.
        </p>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ url('/') }}"
               class="btn px-4"
               style="background:#2176ae; color:white; font-size:14px;">
                Go home
            </a>
            <a href="{{ route('places.index') }}"
               class="btn btn-outline-secondary px-4"
               style="font-size:14px;">
                Browse places
            </a>
        </div>
    </div>

</div>

@endsection