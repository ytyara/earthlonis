@extends('layouts.front')

@section('title', 'All Countries — Earthlonis')
@section('description', 'Browse all countries and discover amazing places around the world.')
@section('og_title', 'All Countries — Earthlonis')
@section('og_description', 'Browse all countries and discover amazing places around the world.')

@section('content')

<section class="bg-light py-4">
    <div class="container">
        <h1 class="mb-1 fs-2 fw-medium text-dark">All Countries</h1>
        <p class="small text-secondary">{{ $countries->count() }} {{ Str::plural('country', $countries->count()) }}</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-3">
            @foreach($countries as $country)
                <div class="col-6 col-md-3">
                    <a href="{{ route('countries.show', $country) }}" class="text-decoration-none">
                        <div class="card border h-100 hover-lift">

                            <div class="thumb-md overflow-hidden bg-placeholder">
                                @if($country->image)
                                    <img src="{{ asset('storage/'.$country->image) }}"
                                         alt="{{ $country->name }}"
                                         class="w-100 h-100 object-fit-cover object-top d-block">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">
                                        No image
                                    </div>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-medium text-dark mb-1 small">{{ $country->name }}</div>
                                <div class="text-muted fs-7">{{ $country->places_count }} {{ Str::plural('place', $country->places_count) }}</div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection