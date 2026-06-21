@extends('layouts.front')

@section('title', $country->name . ' — Earthlonis')
@section('description', 'Explore the best places in ' . $country->name . '. Discover ' . $country->places->count() . ' amazing destinations.')
@section('og_title', $country->name . ' — Earthlonis')
@section('og_description', 'Explore the best places in ' . $country->name . '. Discover ' . $country->places->count() . ' amazing destinations.')
@section('og_image', $country->image ? asset('storage/' . $country->image) : asset('img/og-default.jpg'))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
@endpush

@push('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $country->name, 'item' => route('countries.show', $country)],
    ],
]) !!}
</script>
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@@context' => 'https://schema.org',
    '@type' => 'TouristDestination',
    'name' => $country->name,
    'url' => route('countries.show', $country),
    'description' => 'Explore the best places in ' . $country->name . '. Discover ' . $country->places->count() . ' amazing destinations.',
    'image' => $country->image ? asset('storage/' . $country->image) : null,
])) !!}
</script>
@endpush

@section('content')

{{-- HERO --}}
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center g-4">

            <div class="col-md-6">
                @if($country->image)
                    <img src="{{ asset('storage/'.$country->image) }}"
                         alt="{{ $country->name }}"
                         class="rounded thumb-hero w-100 object-fit-cover object-top">
                @endif
            </div>

            <div class="col-md-6">
                <a href="{{ url('/') }}" class="text-decoration-none small mb-3 d-inline-block text-primary">← All countries</a>
                <h1 class="mb-2 fs-1 fw-medium text-dark">
                    {{ $country->name }}
                </h1>
                <p class="small text-secondary">
                    {{ $country->places->count() }} {{ Str::plural('place', $country->places->count()) }}
                </p>
            </div>

        </div>
    </div>
</section>

{{-- PLACES --}}
<section class="py-5">
    <div class="container">

        <h2 class="mb-4 fs-5 fw-medium">Places in {{ $country->name }}</h2>

        {{-- CATEGORY FILTER --}}
        @include('frontend.partials.category-filter', [
            'allUrl' => route('countries.show', $country),
            'categories' => $categories,
            'categoryUrl' => fn($cat) => route('countries.category', [$country, $cat->slug]),
            'activeCategory' => $category,
        ])

        <div class="row g-3">

            @foreach($country->places as $place)
                <div class="col-12 col-md-4">
                    <a href="{{ route('places.show', $place) }}" class="text-decoration-none">
                        <div class="card border h-100 hover-lift">

                            <div class="thumb-lg overflow-hidden bg-placeholder position-relative">
                                @if($place->image)
                                    <img src="{{ asset('storage/'.$place->image) }}"
                                         alt="{{ $place->title }}"
                                         class="w-100 h-100 object-fit-cover d-block">
                                @endif

                                @if($place->category)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-white bg-opacity-75 text-dark fw-medium fs-8">
                                        {{ $place->category->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="card-body py-3 px-3">
                                <div class="fw-medium text-dark mb-1 small">
                                    {{ $place->title }}
                                </div>
                                <div class="text-muted fs-7">
                                    {{ Str::limit(strip_tags($place->description), 80) }}
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach

            @if($country->places->isEmpty())
                <div class="col-12">
                    <p class="text-muted">No places added yet.</p>
                </div>
            @endif

        </div>

    </div>
</section>

{{-- MAP --}}
@if($country->iso_code)
<section class="pb-5">
    <div class="container">
        <div id="country-map" class="map-md rounded-4 overflow-hidden border"></div>
    </div>
</section>
@endif

@endsection

@push('scripts')
@if($country->iso_code)
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('country-map', {
        zoomControl: true,
        scrollWheelZoom: false,
        dragging: false,
        minZoom: 3,
        maxBounds: [[-85, -180], [85, 180]],
        maxBoundsViscosity: 1.0,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap © CARTO',
        noWrap: true,
    }).addTo(map);

    fetch('/geojson/{{ $country->iso_code }}.json')
        .then(r => r.json())
        .then(data => {
            const layer = L.geoJSON(data, {
                style: {
                    fillColor: '#2176ae',
                    fillOpacity: 0.4,
                    color: '#2176ae',
                    weight: 2
                }
            }).addTo(map);

            map.fitBounds(layer.getBounds(), { padding: [20, 20] });
        })
        .catch(() => {
            map.setView([0, 0], 2);
        });
});
</script>
@endif
@endpush