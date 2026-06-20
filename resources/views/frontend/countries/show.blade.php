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
<section style="background:#eaf4fb; padding:48px 0;">
    <div class="container">
        <div class="row align-items-center g-4">

            <div class="col-md-6">
                @if($country->image)
                    <img src="{{ asset('storage/'.$country->image) }}"
                         alt="{{ $country->name }}"
                         class="rounded"
                         style="width:100%; height:260px; object-fit:cover; object-position:top;">
                @endif
            </div>

            <div class="col-md-6">
                <a href="{{ url('/') }}" class="text-decoration-none small mb-3 d-inline-block"
                   style="color:#2176ae;">← All countries</a>
                <h1 class="mb-2" style="font-size:32px; font-weight:500; color:#0d2a3f;">
                    {{ $country->name }}
                </h1>
                <p style="font-size:14px; color:#4a6a80;">
                    {{ $country->places->count() }} {{ Str::plural('place', $country->places->count()) }}
                </p>
            </div>

        </div>
    </div>
</section>

{{-- PLACES --}}
<section class="py-5">
    <div class="container">

        <h2 class="mb-4" style="font-size:19px; font-weight:500;">Places in {{ $country->name }}</h2>

        {{-- CATEGORY FILTER --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('countries.show', $country) }}"
               class="text-decoration-none px-3 py-2 rounded-pill border small"
               style="background: {{ !$category ? '#2176ae' : 'white' }}; color: {{ !$category ? 'white' : 'var(--bs-body-color)' }}; border-color: {{ !$category ? '#2176ae' : '' }};">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('countries.category', [$country, $cat->slug]) }}"
                   class="text-decoration-none px-3 py-2 rounded-pill border small"
                   style="background: {{ $category?->slug === $cat->slug ? '#2176ae' : 'white' }}; color: {{ $category?->slug === $cat->slug ? 'white' : 'var(--bs-body-color)' }}; border-color: {{ $category?->slug === $cat->slug ? '#2176ae' : '' }}; transition: background 0.15s;"
                   onmouseover="if(this.style.background !== 'rgb(33, 118, 174)') this.style.background='#eaf4fb';"
                   onmouseout="if(this.style.background !== 'rgb(33, 118, 174)') this.style.background='white';">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <div class="row g-3">

            @foreach($country->places as $place)
                <div class="col-12 col-md-4">
                    <a href="{{ route('places.show', $place) }}" class="text-decoration-none">
                        <div class="card border h-100"
                             style="transition: transform 0.15s, border-color 0.15s;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                             onmouseout="this.style.transform=''; this.style.borderColor='';">

                            <div style="height:180px; overflow:hidden; background:#dde8f0; position:relative;">
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
                                <div class="fw-500 text-dark mb-1" style="font-size:14px;">
                                    {{ $place->title }}
                                </div>
                                <div class="text-muted" style="font-size:12px;">
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
        <div id="country-map" style="height:300px; border-radius:12px; overflow:hidden; border:0.5px solid #dee2e6;"></div>
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