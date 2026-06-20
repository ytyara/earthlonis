@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Country</h1>
    <a href="{{ route('backend.countries.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

{{-- STATS --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5 class="text-muted mb-1">Places in this country</h5>
                <h3 class="mb-0">{{ $placesCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h5 class="text-muted mb-1">Categories covered</h5>
                <h3 class="mb-0">{{ $categoriesCount }}</h3>
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

        <form action="{{ route('backend.countries.update', $country) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Country Name</label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $country->name) }}"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- ISO CODE --}}
            <div class="mb-3">
                <label class="form-label">ISO Code
                    <span class="text-muted" style="font-size:12px;">(2 chars, e.g. UA, ID, FR)</span>
                </label>
                <input type="text"
                    name="iso_code"
                    value="{{ old('iso_code', $country->iso_code) }}"
                    class="form-control @error('iso_code') is-invalid @enderror"
                    placeholder="UA"
                    maxlength="2"
                    style="max-width:100px; text-transform:uppercase;">
                @error('iso_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($country->image)
                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div>
                        <img src="{{ asset('storage/'.$country->image) }}"
                             width="200"
                             class="rounded border">
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Upload New Image</label>
                <input type="file"
                       name="image"
                       class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Update Country
            </button>

        </form>

    </div>
</div>

@endsection