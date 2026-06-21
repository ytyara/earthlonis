@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Places</h1>
        <p class="text-muted mb-0">Manage all travel places</p>
    </div>

    <a href="{{ route('backend.places.create') }}" class="btn btn-primary">
        + Add Place
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th width="90">Image</th>
                    <th>Title</th>
                    <th>Country</th>
                    <th>Category</th>
                    <th width="100" class="text-nowrap">
                        @if(request('sort') === 'views')
                            <a href="{{ route('backend.places.index') }}" class="text-decoration-none text-dark">
                                Views <i class="bi bi-sort-down"></i>
                            </a>
                        @else
                            <a href="{{ route('backend.places.index', ['sort' => 'views']) }}" class="text-decoration-none text-dark">
                                Views
                            </a>
                        @endif
                    </th>
                    <th width="110" class="text-nowrap">Been Here</th>
                    <th width="110" class="text-nowrap">Want to Go</th>
                    <th width="150">Created</th>
                    <th width="150">Status</th>
                    <th width="260">Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach($places as $place)

                    <tr>

                        <td>
                            @if($place->image)
                                <img src="{{ asset('storage/'.$place->image) }}"
                                    class="rounded object-fit-cover thumb-table">
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center thumb-table">
                                    <span class="text-muted small">No img</span>
                                </div>
                            @endif
                        </td>

                        <td class="fw-semibold">
                            <a href="{{ route('backend.places.edit', $place) }}" class="text-decoration-none text-dark">
                                {{ $place->title }}
                            </a>
                        </td>

                        <td>{{ $place->country->name }}</td>

                        <td>{{ $place->category?->name ?? '-' }}</td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-eye"></i> {{ number_format($place->views_count) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-flag-fill"></i> {{ number_format($place->been_here_count) }}
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-star-fill"></i> {{ number_format($place->want_to_go_count) }}
                            </span>
                        </td>

                        <td class="text-muted">{{ $place->created_at->diffForHumans() }}</td>

                        <td>
                            @if($place->is_published)
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>

                        <td class="text-end text-nowrap">

                            <div class="d-inline-flex align-items-center gap-1">

                                <a href="{{ route('places.show', $place->slug) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-secondary">
                                    View
                                </a>

                                <form action="{{ route('backend.places.toggle', $place) }}"
                                    method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($place->is_published)
                                        <button class="btn btn-sm btn-outline-warning w-btn-fixed">Unpublish</button>
                                    @else
                                        <button class="btn btn-sm btn-outline-success w-btn-fixed">Publish</button>
                                    @endif
                                </form>

                                <a href="{{ route('backend.places.edit', $place) }}"
                                class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                            </div>

                            <form action="{{ route('backend.places.destroy', $place) }}"
                                method="POST"
                                class="d-inline-block ms-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete place &quot;{{ $place->title }}&quot;? This cannot be undone.')"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete place">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </td>

                    </tr>

                @endforeach

                @if($places->isEmpty())
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            No places yet
                        </td>
                    </tr>
                @endif

            </tbody>

        </table>
    </div>
</div>

<div class="mt-4">
    {{ $places->links('pagination::bootstrap-5') }}
</div>

@endsection