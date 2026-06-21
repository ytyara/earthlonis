@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Create Place</h1>
    <a href="{{ route('backend.places.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

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
              action="{{ route('backend.places.store') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- NAME --}}
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       class="form-control @error('title') is-invalid @enderror"
                       required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- COUNTRY --}}
            <div class="mb-3">
                <label class="form-label">Country</label>
                <select name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
                    <option value="">Select country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                @error('country_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- CATEGORY --}}
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">No category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- COORDINATES --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text"
                           name="latitude"
                           value="{{ old('latitude') }}"
                           placeholder="e.g. 48.1622"
                           class="form-control @error('latitude') is-invalid @enderror">
                    @error('latitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text"
                           name="longitude"
                           value="{{ old('longitude') }}"
                           placeholder="e.g. 24.4994"
                           class="form-control @error('longitude') is-invalid @enderror">
                    @error('longitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- TAGLINE --}}
            <div class="mb-3">
                <label class="form-label">Tagline
                    <span class="text-muted fs-7">(short one-line hook shown under the title, e.g. "The highest peak in Ukraine")</span>
                </label>
                <input type="text"
                       name="tagline"
                       value="{{ old('tagline') }}"
                       class="form-control @error('tagline') is-invalid @enderror">
                @error('tagline')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- KNOW BEFORE YOU GO --}}
            <div class="mb-3">
                <label class="form-label">Know Before You Go
                    <span class="text-muted fs-7">(practical tips, e.g. "How to get there" / "Take the cable car from..." )</span>
                </label>

                <div id="kbyg-rows">
                    @foreach(old('know_before_you_go', []) as $i => $fact)
                        <div class="row g-2 mb-2 kbyg-row">
                            <div class="col-5">
                                <input type="text" name="know_before_you_go[{{ $i }}][label]"
                                       value="{{ $fact['label'] ?? '' }}"
                                       placeholder="Label" class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <input type="text" name="know_before_you_go[{{ $i }}][value]"
                                       value="{{ $fact['value'] ?? '' }}"
                                       placeholder="Value" class="form-control form-control-sm">
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-kbyg-row">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-kbyg-row" class="btn btn-sm btn-outline-primary mt-1">+ Add tip</button>
                @error('know_before_you_go.*.label')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('know_before_you_go.*.value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- QUICK FACTS --}}
            <div class="mb-3">
                <label class="form-label">Quick Facts
                    <span class="text-muted fs-7">(shown as a table under the gallery, e.g. "Height" / "2061 m")</span>
                </label>

                <div id="quick-facts-rows">
                    @foreach(old('quick_facts', []) as $i => $fact)
                        <div class="row g-2 mb-2 quick-fact-row">
                            <div class="col-5">
                                <input type="text" name="quick_facts[{{ $i }}][label]"
                                       value="{{ $fact['label'] ?? '' }}"
                                       placeholder="Label" class="form-control form-control-sm">
                            </div>
                            <div class="col-6">
                                <input type="text" name="quick_facts[{{ $i }}][value]"
                                       value="{{ $fact['value'] ?? '' }}"
                                       placeholder="Value" class="form-control form-control-sm">
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-fact-row">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-fact-row" class="btn btn-sm btn-outline-primary mt-1">+ Add fact</button>
                @error('quick_facts.*.label')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('quick_facts.*.value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <label class="form-label">Story</label>
                <textarea name="description"
                          rows="5"
                          class="form-control">{{ old('description') }}</textarea>
            </div>

            {{-- SOURCES --}}
            <div class="mb-3">
                <label class="form-label">Sources
                    <span class="text-muted fs-7">(links to where this information was sourced from)</span>
                </label>

                <div id="source-rows">
                    @foreach(old('sources', []) as $i => $url)
                        <div class="row g-2 mb-2 source-row">
                            <div class="col-11">
                                <input type="url" name="sources[{{ $i }}]"
                                       value="{{ $url }}"
                                       placeholder="https://example.com/article" class="form-control form-control-sm">
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-source-row">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-source-row" class="btn btn-sm btn-outline-primary mt-1">+ Add source</button>
                @error('sources.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- HERO IMAGE --}}
            <div class="mb-3">
                <label class="form-label">Hero Image</label>
                <input type="file"
                       name="image"
                       class="form-control @error('image') is-invalid @enderror">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- STATUS --}}
            <div class="form-check mb-4">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_published"
                       value="1"
                       {{ old('is_published', true) ? 'checked' : '' }}>
                <label class="form-check-label">Publish immediately</label>
            </div>

            <button class="btn btn-primary">Save Place</button>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const factsContainer = document.getElementById('quick-facts-rows');
    let factIndex = factsContainer.querySelectorAll('.quick-fact-row').length;

    document.getElementById('add-fact-row').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 quick-fact-row';
        row.innerHTML = `
            <div class="col-5">
                <input type="text" name="quick_facts[${factIndex}][label]" placeholder="Label" class="form-control form-control-sm">
            </div>
            <div class="col-6">
                <input type="text" name="quick_facts[${factIndex}][value]" placeholder="Value" class="form-control form-control-sm">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-outline-danger remove-fact-row">×</button>
            </div>
        `;
        factsContainer.appendChild(row);
        factIndex++;
    });

    factsContainer.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-fact-row')) return;
        e.target.closest('.quick-fact-row').remove();
    });

    const kbygContainer = document.getElementById('kbyg-rows');
    let kbygIndex = kbygContainer.querySelectorAll('.kbyg-row').length;

    document.getElementById('add-kbyg-row').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 kbyg-row';
        row.innerHTML = `
            <div class="col-5">
                <input type="text" name="know_before_you_go[${kbygIndex}][label]" placeholder="Label" class="form-control form-control-sm">
            </div>
            <div class="col-6">
                <input type="text" name="know_before_you_go[${kbygIndex}][value]" placeholder="Value" class="form-control form-control-sm">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-outline-danger remove-kbyg-row">×</button>
            </div>
        `;
        kbygContainer.appendChild(row);
        kbygIndex++;
    });

    kbygContainer.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-kbyg-row')) return;
        e.target.closest('.kbyg-row').remove();
    });

    const sourcesContainer = document.getElementById('source-rows');
    let sourceIndex = sourcesContainer.querySelectorAll('.source-row').length;

    document.getElementById('add-source-row').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 source-row';
        row.innerHTML = `
            <div class="col-11">
                <input type="url" name="sources[${sourceIndex}]" placeholder="https://example.com/article" class="form-control form-control-sm">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-outline-danger remove-source-row">×</button>
            </div>
        `;
        sourcesContainer.appendChild(row);
        sourceIndex++;
    });

    sourcesContainer.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-source-row')) return;
        e.target.closest('.source-row').remove();
    });
});
</script>

@endsection
