@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Categories</h1>
        <p class="text-muted mb-0">Manage all categories</p>
    </div>
    <a href="{{ route('backend.categories.create') }}" class="btn btn-primary">
        + Add Category
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th width="160">Places</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>

            <tbody>

            @foreach($categories as $category)
                <tr>
                    <td class="fw-semibold">{{ $category->name }}</td>
                    <td class="text-muted">{{ $category->slug }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $category->places_count }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('backend.categories.edit', $category) }}"
                           class="btn btn-sm btn-outline-primary">
                            Edit
                        </a>

                        <form action="{{ route('backend.categories.destroy', $category) }}"
                              method="POST"
                              class="d-inline-block ms-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Delete category &quot;{{ $category->name }}&quot;? This cannot be undone.')"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Delete category">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

            @if($categories->isEmpty())
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        No categories yet
                    </td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $categories->links('pagination::bootstrap-5') }}
</div>

@endsection