@extends('layouts.front')

@section('title', 'All Countries — Earthlonis')
@section('description', 'Browse all countries and discover amazing places around the world.')
@section('og_title', 'All Countries — Earthlonis')
@section('og_description', 'Browse all countries and discover amazing places around the world.')

@section('content')

<section style="background:#eaf4fb; padding:40px 0;">
    <div class="container">
        <h1 class="mb-1" style="font-size:30px; font-weight:500; color:#0d2a3f;">All Countries</h1>
        <p style="font-size:14px; color:#4a6a80;">{{ $countries->count() }} {{ Str::plural('country', $countries->count()) }}</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-3">
            @foreach($countries as $country)
                <div class="col-6 col-md-3">
                    <a href="{{ route('countries.show', $country) }}" class="text-decoration-none">
                        <div class="card border h-100"
                             style="transition: transform 0.15s, border-color 0.15s;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                             onmouseout="this.style.transform=''; this.style.borderColor='';">

                            <div style="height:130px; overflow:hidden; background:#dde8f0;">
                                @if($country->image)
                                    <img src="{{ asset('storage/'.$country->image) }}"
                                         alt="{{ $country->name }}"
                                         style="width:100%; height:100%; object-fit:cover; object-position:top; display:block;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">
                                        No image
                                    </div>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-500 text-dark mb-1" style="font-size:14px;">{{ $country->name }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $country->places_count }} {{ Str::plural('place', $country->places_count) }}</div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection