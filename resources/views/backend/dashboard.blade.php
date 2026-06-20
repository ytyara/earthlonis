@extends('layouts.backend')

@section('content')

<div class="container py-4">

    <h1 class="mb-4">Dashboard</h1>

    {{-- STATS --}}
    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h2 class="fw-bold">{{ $places }}</h2>
                    <p class="text-muted mb-0">Places</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h2 class="fw-bold">{{ $countries }}</h2>
                    <p class="text-muted mb-0">Countries</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h2 class="fw-bold">{{ $categories }}</h2>
                    <p class="text-muted mb-0">Categories</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h2 class="fw-bold">{{ number_format($totalViews) }}</h2>
                    <p class="text-muted mb-0">Total Views</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        {{-- LATEST PLACES --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="mb-0">Latest Places</h5>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Country</th>
                                <th>Created</th>
                                <th width="80"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestPlaces as $place)
                                <tr>
                                    <td>{{ $place->title }}</td>
                                    <td>{{ $place->country->name ?? '-' }}</td>
                                    <td class="text-muted small">{{ $place->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('backend.places.edit', $place) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @if($latestPlaces->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No places yet</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MOST VIEWED PLACES --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Most Viewed Places</h5>
                    <a href="{{ route('backend.places.index', ['sort' => 'views']) }}" class="small text-decoration-none">
                        View all
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Country</th>
                                <th width="100">Views</th>
                                <th width="80"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mostViewedPlaces as $place)
                                <tr>
                                    <td>{{ $place->title }}</td>
                                    <td>{{ $place->country->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-eye"></i> {{ number_format($place->views_count) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.places.edit', $place) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            @if($mostViewedPlaces->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No views yet</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection