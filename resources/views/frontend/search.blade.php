@extends('layouts.front')

@section('title', $query ? 'Search: ' . $query . ' — Earthlonis' : 'Search — Earthlonis')
@section('canonical', route('search'))

@section('content')

<section style="background:#eaf4fb; padding:40px 0;">
    <div class="container">
        <h1 class="mb-3" style="font-size:28px; font-weight:500; color:#0d2a3f;">Search</h1>

        <form action="{{ route('search') }}" method="GET"
              class="d-flex" style="max-width:500px;">
            <input type="text"
                   name="q"
                   value="{{ $query }}"
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

    </div>
</section>

<section class="py-5">
    <div class="container">

        @if($query)

            {{-- RESULTS COUNT --}}
            <p class="mb-4 text-muted" style="font-size:14px;">
                Found {{ $places->count() + $countries->count() }} results for
                <strong>"{{ $query }}"</strong>
            </p>

            {{-- COUNTRIES --}}
            @if($countries->count())
                <h2 class="mb-3" style="font-size:17px; font-weight:500;">Countries</h2>
                <div class="row g-3 mb-5">
                    @foreach($countries as $country)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('countries.show', $country) }}" class="text-decoration-none">
                                <div class="card border h-100"
                                     style="transition: transform 0.15s, border-color 0.15s;"
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                                     onmouseout="this.style.transform=''; this.style.borderColor='';">
                                    <div style="height:110px; overflow:hidden; background:#dde8f0;">
                                        @if($country->image)
                                            <img src="{{ asset('storage/'.$country->image) }}"
                                                 alt="{{ $country->name }}"
                                                 style="width:100%; height:100%; object-fit:cover; object-position:top; display:block;">
                                        @endif
                                    </div>
                                    <div class="card-body py-2 px-3">
                                        <div class="fw-500 text-dark" style="font-size:14px;">{{ $country->name }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- PLACES --}}
            @if($places->count())
                <h2 class="mb-3" style="font-size:17px; font-weight:500;">Places</h2>
                <div class="row g-3">
                    @foreach($places as $place)
                        <div class="col-12 col-md-4">
                            <a href="{{ route('places.show', $place->slug) }}"
                               class="text-decoration-none">
                                <div class="card border h-100"
                                     style="transition: transform 0.15s, border-color 0.15s;"
                                     onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='#aac4d8';"
                                     onmouseout="this.style.transform=''; this.style.borderColor='';">
                                    <div style="height:160px; overflow:hidden; background:#dde8f0; position:relative;">
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
                                        <div class="text-muted d-flex align-items-center gap-2" style="font-size:12px;">
                                            <span>{{ $place->country->name }}</span>
                                            @if($place->category)
                                                <span style="width:3px; height:3px; border-radius:50%; background:#ccc; display:inline-block;"></span>
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
                    <p style="font-size:16px; color:#4a6a80;">No results found for <strong>"{{ $query }}"</strong></p>
                    <p class="text-muted" style="font-size:14px;">Try a different search term.</p>
                </div>
            @endif

        @else
            <p class="text-muted" style="font-size:14px;">Enter a search term above to find countries and places.</p>
        @endif

    </div>
</section>

@endsection