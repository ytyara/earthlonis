@extends('layouts.front')

@section('title', 'All Places — Earthlonis')
@section('description', 'Explore our curated collection of the most remarkable places on Earth.')
@section('og_title', 'All Places — Earthlonis')
@section('og_description', 'Explore our curated collection of the most remarkable places on Earth.')

@section('content')

<section style="background:#eaf4fb; padding:40px 0;">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1" style="font-size:30px; font-weight:500; color:#0d2a3f;">All Places</h1>
            <p class="mb-0" style="font-size:14px; color:#4a6a80;">{{ $places->total() }} places</p>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">

        {{-- CATEGORY FILTER --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('places.index') }}"
               class="text-decoration-none px-3 py-2 rounded-pill border small"
               style="background: {{ !$category ? '#2176ae' : 'white' }}; color: {{ !$category ? 'white' : 'var(--bs-body-color)' }}; border-color: {{ !$category ? '#2176ae' : '' }};">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('places.category', $cat->slug) }}"
                   class="text-decoration-none px-3 py-2 rounded-pill border small"
                   style="background: {{ $category?->slug === $cat->slug ? '#2176ae' : 'white' }}; color: {{ $category?->slug === $cat->slug ? 'white' : 'var(--bs-body-color)' }}; border-color: {{ $category?->slug === $cat->slug ? '#2176ae' : '' }}; transition: background 0.15s;"
                   onmouseover="if(this.style.background !== 'rgb(33, 118, 174)') this.style.background='#eaf4fb';"
                   onmouseout="if(this.style.background !== 'rgb(33, 118, 174)') this.style.background='white';">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        {{-- PLACES GRID --}}
        <div class="row g-3">
            @foreach($places as $place)
                <div class="col-12 col-md-4">
                    <a href="{{ route('places.show', $place->slug) }}"
                       class="text-decoration-none">
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
                                <div class="fw-500 text-dark mb-1" style="font-size:14px;">{{ $place->title }}</div>
                                <div class="text-muted d-flex align-items-center gap-2" style="font-size:12px;">
                                    <span>{{ $place->country->name }}</span>
                                    <span style="width:3px; height:3px; border-radius:50%; background:#ccc; display:inline-block;"></span>
                                    <span>{{ $place->category?->name ?? '' }}</span>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            {{ $places->links('pagination::bootstrap-5') }}
        </div>

    </div>
</section>

@endsection