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
<section style="background:#eaf4fb; padding: 64px 0 72px; text-align:center;">
    <div class="container">

        <span class="badge rounded-pill mb-3"
              style="background:rgba(33,118,174,0.1); color:#2176ae; border:0.5px solid rgba(33,118,174,0.2); font-size:11px; letter-spacing:0.08em; text-transform:uppercase; padding:5px 14px;">
            Discover the world
        </span>

        <h1 class="fw-500 mb-3" style="font-size:48px; color:#0d2a3f; line-height:1.2;">
            Explore <span style="color:#2176ae;">remarkable</span><br>places on Earth
        </h1>

        <p class="mb-4 mx-auto" style="font-size:15px; color:#4a6a80; max-width:460px; line-height:1.7;">
            A curated travel catalog of the most beautiful and fascinating destinations across the globe.
        </p>

        <form action="{{ route('search') }}" method="GET"
            class="d-flex mx-auto" style="max-width:420px;">
            <input type="text"
                name="q"
                class="form-control rounded-end-0"
                placeholder="Search countries, places..."
                style="border-color:#b8d4e8;"
                autocomplete="off">
            <button type="submit"
                    class="btn rounded-start-0 px-4"
                    style="background:#2176ae; color:#fff; font-size:14px;">
                Search
            </button>
        </form>

        <div class="d-flex justify-content-center gap-5 mt-5">
            <div>
                <div class="fw-500 fs-4" style="color:#0d2a3f;">{{ $countriesCount }}</div>
                <div class="text-uppercase" style="font-size:11px; color:#4a6a80; letter-spacing:0.06em;">Countries</div>
            </div>
            <div>
                <div class="fw-500 fs-4" style="color:#0d2a3f;">{{ $categoriesCount }}</div>
                <div class="text-uppercase" style="font-size:11px; color:#4a6a80; letter-spacing:0.06em;">Categories</div>
            </div>
            <div>
                <div class="fw-500 fs-4" style="color:#0d2a3f;">{{ $placesCount }}</div>
                <div class="text-uppercase" style="font-size:11px; color:#4a6a80; letter-spacing:0.06em;">Places</div>
            </div>
            <div>
                <div class="fw-500 fs-4" style="color:#0d2a3f;">{{ $commentsCount }}</div>
                <div class="text-uppercase" style="font-size:11px; color:#4a6a80; letter-spacing:0.06em;">Comments</div>
            </div>
        </div>

    </div>
</section>

{{-- COUNTRIES --}}
<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-baseline mb-2">
            <h2 class="fw-500 mb-0" style="font-size:19px;">Browse by country</h2>
            <a href="{{ route('countries.index') }}" class="text-decoration-none small" style="color:#2176ae;">View all →</a>
        </div>
        <p class="mb-4" style="font-size:13px; color:#4a6a80;">
            Pick a country and dive into everything it has to offer — from quiet villages to iconic landmarks.
        </p>

        <div class="row g-3">
            @foreach($countries as $country)
                <div class="col-6 col-md-3">
                    <a href="{{ route('countries.show', $country) }}" class="text-decoration-none">
                        <div class="card border h-100"
                             style="border-color: var(--bs-border-color); transition: transform 0.15s, border-color 0.15s;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                             onmouseout="this.style.transform=''; this.style.borderColor='';">

                            <div style="height:180px; overflow:hidden; background:#dde8f0;">
                                @if($country->image)
                                    <img src="{{ asset('storage/'.$country->image) }}"
                                         alt="{{ $country->name }}"
                                         style="width:100%; height:100%; object-fit:cover; display:block;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">
                                        No image
                                    </div>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-500 text-dark mb-1" style="font-size:14px;">{{ $country->name }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $country->places_count }} places</div>
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
            <h2 class="fw-500 mb-0" style="font-size:19px;">Trending Now</h2>
            <a href="{{ route('places.index') }}" class="text-decoration-none small" style="color:#2176ae;">View all →</a>
        </div>
        <p class="mb-4" style="font-size:13px; color:#4a6a80;">
            The places our readers are visiting right now, ranked by page views.
        </p>

        <div class="row g-3">
            @foreach($trendingPlaces as $place)
                <div class="col-6 col-md-3">
                    <a href="{{ route('places.show', $place->slug) }}"
                       class="text-decoration-none">
                        <div class="card border h-100"
                             style="transition: transform 0.15s, border-color 0.15s;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                             onmouseout="this.style.transform=''; this.style.borderColor='';">

                            <div style="height:140px; overflow:hidden; background:#dde8f0; position:relative;">
                                @if($place->image)
                                    <img src="{{ asset('storage/'.$place->image) }}"
                                         alt="{{ $place->title }}"
                                         style="width:100%; height:100%; object-fit:cover; display:block;">
                                @endif

                                <span class="position-absolute top-0 end-0 m-2 badge d-flex align-items-center gap-1"
                                      style="background:rgba(255,255,255,0.92); color:#0d2a3f; font-size:11px; font-weight:500;">
                                    <i class="bi bi-eye"></i> {{ number_format($place->views_count) }}
                                </span>

                                @if($place->category)
                                    <span class="position-absolute top-0 start-0 m-2 badge"
                                          style="background:rgba(255,255,255,0.92); color:#0d2a3f; font-size:11px; font-weight:500;">
                                        {{ $place->category->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-500 text-dark mb-1" style="font-size:14px;">{{ $place->title }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $place->country->name }}</div>
                            </div>

                        </div>
                    </a>
                </div>
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
            <h2 class="fw-500 mb-0" style="font-size:19px;">Most Wanted</h2>
            <a href="{{ route('places.index') }}" class="text-decoration-none small" style="color:#2176ae;">View all →</a>
        </div>
        <p class="mb-4" style="font-size:13px; color:#4a6a80;">
            Destinations most readers have added to their travel wishlist.
        </p>

        <div class="row g-3">
            @foreach($mostWantedPlaces as $place)
                <div class="col-6 col-md-3">
                    <a href="{{ route('places.show', $place->slug) }}"
                       class="text-decoration-none">
                        <div class="card border h-100"
                             style="transition: transform 0.15s, border-color 0.15s;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                             onmouseout="this.style.transform=''; this.style.borderColor='';">

                            <div style="height:140px; overflow:hidden; background:#dde8f0; position:relative;">
                                @if($place->image)
                                    <img src="{{ asset('storage/'.$place->image) }}"
                                         alt="{{ $place->title }}"
                                         style="width:100%; height:100%; object-fit:cover; display:block;">
                                @endif

                                <span class="position-absolute top-0 end-0 m-2 badge d-flex align-items-center gap-1"
                                      style="background:rgba(255,255,255,0.92); color:#0d2a3f; font-size:11px; font-weight:500;">
                                    <i class="bi bi-star-fill" style="color:#2176ae;"></i> {{ number_format($place->want_to_go_count) }}
                                </span>

                                @if($place->category)
                                    <span class="position-absolute top-0 start-0 m-2 badge"
                                          style="background:rgba(255,255,255,0.92); color:#0d2a3f; font-size:11px; font-weight:500;">
                                        {{ $place->category->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-500 text-dark mb-1" style="font-size:14px;">{{ $place->title }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $place->country->name }}</div>
                            </div>

                        </div>
                    </a>
                </div>
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
            <h2 class="fw-500 mb-0" style="font-size:19px;">Newest Additions</h2>
            <a href="{{ route('places.index') }}" class="text-decoration-none small" style="color:#2176ae;">View all →</a>
        </div>
        <p class="mb-4" style="font-size:13px; color:#4a6a80;">
            Freshly added to the catalog — be among the first to explore these places.
        </p>

        <div class="row g-3">
            @foreach($newestPlaces as $place)
                <div class="col-6 col-md-3">
                    <a href="{{ route('places.show', $place->slug) }}"
                       class="text-decoration-none">
                        <div class="card border h-100"
                             style="transition: transform 0.15s, border-color 0.15s;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                             onmouseout="this.style.transform=''; this.style.borderColor='';">

                            <div style="height:140px; overflow:hidden; background:#dde8f0; position:relative;">
                                @if($place->image)
                                    <img src="{{ asset('storage/'.$place->image) }}"
                                         alt="{{ $place->title }}"
                                         style="width:100%; height:100%; object-fit:cover; display:block;">
                                @endif

                                @if($place->category)
                                    <span class="position-absolute top-0 start-0 m-2 badge"
                                          style="background:rgba(255,255,255,0.92); color:#0d2a3f; font-size:11px; font-weight:500;">
                                        {{ $place->category->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-500 text-dark mb-1" style="font-size:14px;">{{ $place->title }}</div>
                                <div class="text-muted" style="font-size:12px;">{{ $place->country->name }}</div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</section>

<hr class="mx-4">
@endif

{{-- CATEGORIES --}}
<section class="py-5">
    <div class="container">

        <h2 class="fw-500 mb-2" style="font-size:19px;">Browse by category</h2>
        <p class="mb-4" style="font-size:13px; color:#4a6a80;">
            Looking for something specific? Filter places by the kind of experience you're after.
        </p>

        <div class="d-flex flex-wrap gap-2">
            @foreach($categories as $category)
                <a href="{{ route('places.category', $category->slug) }}"
                   class="text-decoration-none d-flex align-items-center gap-2 px-4 py-2 rounded-pill border"
                   style="font-size:13px; color: var(--bs-body-color); background:white; transition: background 0.15s, border-color 0.15s;"
                   onmouseover="this.style.background='#eaf4fb'; this.style.borderColor='#b8d4e8';"
                   onmouseout="this.style.background='white'; this.style.borderColor='';">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

    </div>
</section>

{{-- ABOUT --}}
<section class="py-5" style="background:#eaf4fb;">
    <div class="container" style="max-width:640px; text-align:center;">
        <h2 class="fw-500 mb-3" style="font-size:19px; color:#0d2a3f;">About Earthlonis</h2>
        <p style="font-size:14px; color:#4a6a80; line-height:1.8;">
            Earthlonis is a community-curated catalog of remarkable places — from world-famous landmarks
            to quiet corners few travelers ever find. Every entry is reviewed before publishing, and our
            readers help keep it alive by sharing their own visits, wishlists, and comments. Whether you're
            planning your next trip or just love exploring the map, we hope you find something worth adding
            to your own list.
        </p>
    </div>
</section>

@endsection