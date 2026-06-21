@extends('layouts.front')

@section('title', 'All Places — Earthlonis')
@section('description', 'Explore our curated collection of the most remarkable places on Earth.')
@section('og_title', 'All Places — Earthlonis')
@section('og_description', 'Explore our curated collection of the most remarkable places on Earth.')

@section('content')

<section class="bg-light py-4">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1 fs-2 fw-medium text-dark">All Places</h1>
            <p class="mb-0 small text-secondary">{{ $places->total() }} places</p>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">

        {{-- CATEGORY FILTER --}}
        @include('frontend.partials.category-filter', [
            'allUrl' => route('places.index'),
            'categories' => $categories,
            'categoryUrl' => fn($cat) => route('places.category', $cat->slug),
            'activeCategory' => $category,
        ])

        {{-- PLACES GRID --}}
        <div class="row g-3">
            @foreach($places as $place)
                <div class="col-12 col-md-4">
                    <a href="{{ route('places.show', $place->slug) }}"
                       class="text-decoration-none">
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
                                <div class="fw-medium text-dark mb-1 small">{{ $place->title }}</div>
                                <div class="text-muted d-flex align-items-center gap-2 fs-7">
                                    <span>{{ $place->country->name }}</span>
                                    <span class="dot-sep"></span>
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