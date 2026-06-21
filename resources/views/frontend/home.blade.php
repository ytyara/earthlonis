@extends('layouts.front')

@section('title', 'Earthlonis — Discover remarkable places on Earth')
@section('description', 'A curated travel catalog of the most beautiful and fascinating destinations across the globe.')
@section('og_title', 'Earthlonis — Discover remarkable places on Earth')
@section('og_description', 'A curated travel catalog of the most beautiful and fascinating destinations across the globe.')

@push('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'Earthlonis',
    'url' => url('/'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => route('search') . '?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
]) !!}
</script>
@endpush

@section('content')

{{-- HERO --}}
<section class="bg-light py-6 text-center">
    <div class="container">

        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle fs-7 text-uppercase tracked-wide px-3 py-2 mb-4">
            Discover the world
        </span>

        <h1 class="display-3 fw-medium text-dark mb-4">
            Explore <span class="text-primary">remarkable</span><br>places on Earth
        </h1>

        <p class="fs-5 text-secondary mx-auto mw-prose mb-5 lh-lg">
            Earthlonis is a curated travel catalog of the most beautiful and fascinating destinations
            across the globe — from towering peaks to hidden villages. Every place is handpicked,
            reviewed, and organized by country and category, so you can go straight from daydreaming
            to planning your next trip.
        </p>

        <form action="{{ route('search') }}" method="GET" class="d-flex mx-auto mw-search">
            <input type="text"
                name="q"
                class="form-control form-control-lg rounded-end-0 border-brand-soft"
                placeholder="Search countries, places..."
                autocomplete="off">
            <button type="submit" class="btn btn-primary btn-lg rounded-start-0 px-5">
                Search
            </button>
        </form>

        <div class="d-flex justify-content-center gap-6 mt-5">
            <div>
                <div class="fs-1 fw-semibold text-dark">{{ $countriesCount }}</div>
                <div class="text-uppercase tracked fs-7 text-secondary mt-1">Countries</div>
            </div>
            <div>
                <div class="fs-1 fw-semibold text-dark">{{ $categoriesCount }}</div>
                <div class="text-uppercase tracked fs-7 text-secondary mt-1">Categories</div>
            </div>
            <div>
                <div class="fs-1 fw-semibold text-dark">{{ $placesCount }}</div>
                <div class="text-uppercase tracked fs-7 text-secondary mt-1">Places</div>
            </div>
        </div>

    </div>
</section>

{{-- COUNTRIES --}}
<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-baseline mb-2">
            <h2 class="fw-medium fs-5 mb-0">Browse by country</h2>
            <a href="{{ route('countries.index') }}" class="text-decoration-none small text-primary">View all →</a>
        </div>
        <p class="mb-4 small text-secondary">
            Pick a country and dive into everything it has to offer — from quiet villages to iconic landmarks.
        </p>

        <div class="row g-3">
            @foreach($countries as $country)
                <div class="col-6 col-md-3">
                    <a href="{{ route('countries.show', $country) }}" class="text-decoration-none">
                        <div class="card border h-100 hover-lift">

                            <div class="thumb-lg overflow-hidden bg-placeholder">
                                @if($country->image)
                                    <img src="{{ asset('storage/'.$country->image) }}"
                                         alt="{{ $country->name }}"
                                         class="w-100 h-100 object-fit-cover d-block">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">
                                        No image
                                    </div>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-medium text-dark mb-1 small">{{ $country->name }}</div>
                                <div class="text-muted fs-7">{{ $country->places_count }} places</div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</section>

<hr class="mx-4">

{{-- TRENDING NOW --}}
@if($trendingPlaces->count())
<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-baseline mb-2">
            <h2 class="fw-medium fs-5 mb-0">Trending Now</h2>
            <a href="{{ route('places.index') }}" class="text-decoration-none small text-primary">View all →</a>
        </div>
        <p class="mb-4 small text-secondary">
            The places our readers are visiting right now, ranked by page views.
        </p>

        <div class="row g-3">
            @foreach($trendingPlaces as $place)
                @include('frontend.partials.place-card', [
                    'place' => $place,
                    'metricIcon' => 'bi-eye',
                    'metricValue' => number_format($place->views_count),
                ])
            @endforeach
        </div>

    </div>
</section>

<hr class="mx-4">
@endif

{{-- MOST WANTED --}}
@if($mostWantedPlaces->count())
<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-baseline mb-2">
            <h2 class="fw-medium fs-5 mb-0">Most Wanted</h2>
            <a href="{{ route('places.index') }}" class="text-decoration-none small text-primary">View all →</a>
        </div>
        <p class="mb-4 small text-secondary">
            Destinations most readers have added to their travel wishlist.
        </p>

        <div class="row g-3">
            @foreach($mostWantedPlaces as $place)
                @include('frontend.partials.place-card', [
                    'place' => $place,
                    'metricIcon' => 'bi-star-fill',
                    'metricIconClass' => 'text-primary',
                    'metricValue' => number_format($place->want_to_go_count),
                ])
            @endforeach
        </div>

    </div>
</section>

<hr class="mx-4">
@endif

{{-- NEWEST ADDITIONS --}}
@if($newestPlaces->count())
<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-baseline mb-2">
            <h2 class="fw-medium fs-5 mb-0">Newest Additions</h2>
            <a href="{{ route('places.index') }}" class="text-decoration-none small text-primary">View all →</a>
        </div>
        <p class="mb-4 small text-secondary">
            Freshly added to the catalog — be among the first to explore these places.
        </p>

        <div class="row g-3">
            @foreach($newestPlaces as $place)
                @include('frontend.partials.place-card', ['place' => $place])
            @endforeach
        </div>

    </div>
</section>

<hr class="mx-4">
@endif

{{-- CATEGORIES --}}
<section class="py-5">
    <div class="container">

        <h2 class="fw-medium fs-5 mb-2">Browse by category</h2>
        <p class="mb-4 small text-secondary">
            Looking for something specific? Filter places by the kind of experience you're after.
        </p>

        <div class="d-flex flex-wrap gap-2">
            @foreach($categories as $category)
                <a href="{{ route('places.category', $category->slug) }}"
                   class="text-decoration-none d-flex align-items-center gap-2 px-4 py-2 rounded-pill border bg-white small hover-pill">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

    </div>
</section>

{{-- ABOUT --}}
<section class="py-5 bg-light">
    <div class="container mw-prose text-center">
        <h2 class="fw-medium fs-5 text-dark mb-3">About Earthlonis</h2>
        <p class="text-secondary lh-lg">
            Earthlonis is a community-curated catalog of remarkable places — from world-famous landmarks
            to quiet corners few travelers ever find. Every entry is reviewed before publishing, and our
            readers help keep it alive by sharing their own visits, wishlists, and comments. Whether you're
            planning your next trip or just love exploring the map, we hope you find something worth adding
            to your own list.
        </p>
    </div>
</section>

@endsection