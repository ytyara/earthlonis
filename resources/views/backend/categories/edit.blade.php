@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Category</h1>
    <a href="{{ route('backend.categories.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

{{-- STATS --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5 class="text-muted mb-1">Places in this category</h5>
                <h3 class="mb-0">{{ $placesCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5 class="text-muted mb-1">Countries covered</h5>
                <h3 class="mb-0">{{ $countriesCount }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- FORM --}}
<div class="card shadow-sm">
    <div class="card-body">

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('backend.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $category->name) }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Update Category</button>

        </form>

    </div>
</div>

@endsection