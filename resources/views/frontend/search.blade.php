@extends('layouts.front')

@section('title', $query ? 'Search: ' . $query . ' — Earthlonis' : 'Search — Earthlonis')
@section('canonical', route('search'))

@section('content')

<section class="bg-light py-4">
    <div class="container">
        <h1 class="mb-3 fs-3 fw-medium text-dark">Search</h1>

        <form action="{{ route('search') }}" method="GET" class="d-flex mw-search">
            <input type="text"
                   name="q"
                   value="{{ $query }}"
                   class="form-control rounded-end-0 border-brand-soft"
                   placeholder="Search countries, places..."
                   autocomplete="off">
            <button type="submit" class="btn btn-primary rounded-start-0 px-4">
                Search
            </button>
        </form>

    </div>
</section>

<section class="py-5">
    <div class="container">

        @if($query)

            {{-- RESULTS COUNT --}}
            <p class="mb-4 text-muted small">
                Found {{ $places->count() + $countries->count() }} results for
                <strong>"{{ $query }}"</strong>
            </p>

            {{-- COUNTRIES --}}
            @if($countries->count())
                <h2 class="mb-3 fs-6 fw-medium">Countries</h2>
                <div class="row g-3 mb-5">
                    @foreach($countries as $country)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('countries.show', $country) }}" class="text-decoration-none">
                                <div class="card border h-100 hover-lift">
                                    <div class="thumb-sm overflow-hidden bg-placeholder">
                                        @if($country->image)
                                            <img src="{{ asset('storage/'.$country->image) }}"
                                                 alt="{{ $country->name }}"
                                                 class="w-100 h-100 object-fit-cover object-top d-block">
                                        @endif
                                    </div>
                                    <div class="card-body py-2 px-3">
                                        <div class="fw-medium text-dark small">{{ $country->name }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- PLACES --}}
            @if($places->count())
                <h2 class="mb-3 fs-6 fw-medium">Places</h2>
                <div class="row g-3">
                    @foreach($places as $place)
                        <div class="col-12 col-md-4">
                            <a href="{{ route('places.show', $place->slug) }}"
                               class="text-decoration-none">
                                <div class="card border h-100 hover-lift">
                                    <div class="thumb-md overflow-hidden bg-placeholder position-relative">
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
                                        <div class="fw-medium text-dark mb-1 small">{{ $place->title }}</div>
                                        <div class="text-muted d-flex align-items-center gap-2 fs-7">
                                            <span>{{ $place->country->name }}</span>
                                            @if($place->category)
                                                <span class="dot-sep"></span>
                                                <span>{{ $place->category->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- NO RESULTS --}}
            @if($places->isEmpty() && $countries->isEmpty())
                <div class="text-center py-5">
                    <p class="text-secondary">No results found for <strong>"{{ $query }}"</strong></p>
                    <p class="text-muted small">Try a different search term.</p>
                </div>
            @endif

        @else
            <p class="text-muted small">Enter a search term above to find countries and places.</p>
        @endif

    </div>
</section>

@endsection