@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Countries</h1>
        <p class="text-muted mb-0">Manage all countries</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead class="table-light">
                <tr>
                    <th width="80">Image</th>
                    <th>Name</th>
                    <th width="100">Places</th>
                    <th width="220">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($countries as $country)
                    <tr>

                        <td>
                            @if($country->image)
                                <img src="{{ asset('storage/'.$country->image) }}"
                                     width="60"
                                     class="rounded">
                            @endif
                        </td>

                        <td class="fw-semibold">
                            {{ $country->name }}
                        </td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ $country->places_count }}
                            </span>
                        </td>

                        <td>
                            <div class="d-inline-flex align-items-center gap-1">

                                <a href="{{ route('countries.show', $country) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-secondary">
                                    View
                                </a>

                                <a href="{{ route('backend.countries.edit', $country) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                            </div>

                            <form action="{{ route('backend.countries.destroy', $country) }}"
                                  method="POST"
                                  class="d-inline-block ms-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete country &quot;{{ $country->name }}&quot;? This will also delete all its places. This cannot be undone.')"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete country">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

<div class="mt-4">
    {{ $countries->links('pagination::bootstrap-5') }}
</div>

@endsection