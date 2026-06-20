@extends('layouts.backend')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Place</h1>
    <a href="{{ route('backend.places.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

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
      action="{{ route('backend.places.update', $place) }}"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- TABS --}}
    <ul class="nav nav-tabs mb-0" id="placeTabs">
        <li class="nav-item">
            <button class="nav-link active text-dark" data-bs-toggle="tab" data-bs-target="#tab-main" type="button">Main</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-dark" data-bs-toggle="tab" data-bs-target="#tab-media" type="button">Media</button>
        </li>
        <li class="nav-item">
            <button class="nav-link text-dark" data-bs-toggle="tab" data-bs-target="#tab-seo" type="button">SEO</button>
        </li>
    </ul>

    <div class="tab-content border border-top-0 rounded-bottom p-4 bg-white mb-4">

        {{-- TAB MAIN --}}
        <div class="tab-pane fade show active" id="tab-main">

            {{-- STATUS --}}
            <div class="mb-4 p-3 rounded" id="status-box"
                 style="background:{{ $place->is_published ? '#d1f2dd' : '#f1f3f5' }};">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input"
                           type="checkbox"
                           role="switch"
                           id="is_published"
                           name="is_published"
                           value="1"
                           {{ old('is_published', $place->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label fw-500" for="is_published" id="status-label">
                        {{ $place->is_published ? 'Published' : 'Draft' }}
                        <span class="text-muted" style="font-size:12px;">— visible on the public site only when published</span>
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $place->title) }}"
                       class="form-control @error('title') is-invalid @enderror"
                       required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Country</label>
                <select name="country_id" class="form-select @error('country_id') is-invalid @enderror" required>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                            {{ $place->country_id == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                @error('country_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">No category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $place->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Latitude</label>
                    <input type="text"
                           name="latitude"
                           value="{{ old('latitude', $place->latitude) }}"
                           placeholder="e.g. 48.1622"
                           class="form-control @error('latitude') is-invalid @enderror">
                    @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Longitude</label>
                    <input type="text"
                           name="longitude"
                           value="{{ old('longitude', $place->longitude) }}"
                           placeholder="e.g. 24.4994"
                           class="form-control @error('longitude') is-invalid @enderror">
                    @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tagline
                    <span class="text-muted" style="font-size:12px;">(short one-line hook shown under the title, e.g. "The highest peak in Ukraine")</span>
                </label>
                <input type="text"
                       name="tagline"
                       value="{{ old('tagline', $place->tagline) }}"
                       class="form-control @error('tagline') is-invalid @enderror">
                @error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Know Before You Go
                    <span class="text-muted" style="font-size:12px;">(practical tips, e.g. "How to get there" / "Take the cable car from..." )</span>
                </label>

                <div id="kbyg-rows">
                    @forelse(old('know_before_you_go', $place->know_before_you_go ?? []) as $i => $fact)
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
                    @empty
                    @endforelse
                </div>

                <button type="button" id="add-kbyg-row" class="btn btn-sm btn-outline-primary mt-1">+ Add tip</button>
                @error('know_before_you_go.*.label')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('know_before_you_go.*.value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Quick Facts
                    <span class="text-muted" style="font-size:12px;">(shown as a table under the gallery, e.g. "Height" / "2061 m")</span>
                </label>

                <div id="quick-facts-rows">
                    @forelse(old('quick_facts', $place->quick_facts ?? []) as $i => $fact)
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
                    @empty
                    @endforelse
                </div>

                <button type="button" id="add-fact-row" class="btn btn-sm btn-outline-primary mt-1">+ Add fact</button>
                @error('quick_facts.*.label')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('quick_facts.*.value')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Story</label>
                <textarea name="description"
                          id="description"
                          rows="6"
                          class="form-control">{{ old('description', $place->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Sources
                    <span class="text-muted" style="font-size:12px;">(links to where this information was sourced from)</span>
                </label>

                <div id="source-rows">
                    @forelse(old('sources', $place->sources ?? []) as $i => $url)
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
                    @empty
                    @endforelse
                </div>

                <button type="button" id="add-source-row" class="btn btn-sm btn-outline-primary mt-1">+ Add source</button>
                @error('sources.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mt-4">
                <button type="submit" name="redirect_to" value="edit" class="btn btn-outline-primary">
                    Update
                </button>
                <button type="submit" name="redirect_to" value="index" class="btn btn-primary">
                    Update and Close
                </button>
            </div>

        </div>

        {{-- TAB MEDIA --}}
        <div class="tab-pane fade" id="tab-media">

            @if($place->image)
                <div class="mb-3">
                    <label class="form-label">Current Hero Image</label>
                    <div>
                        <img src="{{ asset('storage/'.$place->image) }}"
                             class="rounded border"
                             style="max-width: 200px;">
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label class="form-label">Change Hero Image
                    <span class="text-muted" style="font-size:12px;">(max 2MB)</span>
                </label>
                <input type="file"
                       name="image"
                       class="form-control @error('image') is-invalid @enderror">
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- GALLERY --}}
            <hr>
            <h5 class="mb-3">Gallery</h5>

            <div class="row mb-3" id="gallery-container">
                @foreach($place->images as $image)
                    <div class="col-md-3 mb-3" id="image-{{ $image->id }}">
                        <div class="card">
                            <img src="{{ asset('storage/'.$image->image) }}"
                                 class="card-img-top"
                                 style="height: 150px; object-fit: cover;">
                            <div class="card-body text-center p-2">
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger delete-image"
                                        data-url="{{ route('backend.places.gallery.delete', $image) }}"
                                        data-id="{{ $image->id }}">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($place->images->isEmpty())
                    <p id="no-images-text" class="text-muted">No images yet.</p>
                @endif
            </div>

            <div id="gallery-upload-section">
                <h6 class="mb-2">Add Images</h6>
                <div class="d-flex gap-2 align-items-center">
                    <input type="file"
                           name="images[]"
                           id="gallery-input"
                           class="form-control"
                           multiple>
                    <button type="button" id="gallery-upload-btn" class="btn btn-outline-primary btn-sm text-nowrap">
                        Upload
                    </button>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="redirect_to" value="edit" class="btn btn-outline-primary">
                    Update
                </button>
                <button type="submit" name="redirect_to" value="index" class="btn btn-primary">
                    Update and Close
                </button>
            </div>

        </div>

        {{-- TAB SEO --}}
        <div class="tab-pane fade" id="tab-seo">

            <div class="mb-3">
                <label class="form-label">Meta Title
                    <span class="text-muted" style="font-size:12px;">(recommended: 50-60 chars)</span>
                </label>
                <input type="text"
                       name="meta_title"
                       value="{{ old('meta_title', $place->meta_title) }}"
                       class="form-control @error('meta_title') is-invalid @enderror"
                       placeholder="{{ $place->title }} — Earthlonis"
                       id="meta-title-input">
                <div class="d-flex justify-content-between mt-1">
                    <div>@error('meta_title')<span class="text-danger small">{{ $message }}</span>@enderror</div>
                    <small class="text-muted" id="meta-title-count">0 chars</small>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Meta Description
                    <span class="text-muted" style="font-size:12px;">(recommended: 150-160 chars)</span>
                </label>
                <textarea name="meta_description"
                          rows="3"
                          class="form-control @error('meta_description') is-invalid @enderror"
                          placeholder="Describe this place in 150-160 characters..."
                          id="meta-desc-input">{{ old('meta_description', $place->meta_description) }}</textarea>
                <div class="d-flex justify-content-between mt-1">
                    <div>@error('meta_description')<span class="text-danger small">{{ $message }}</span>@enderror</div>
                    <small class="text-muted" id="meta-desc-count">0 chars</small>
                </div>
            </div>

            {{-- GOOGLE PREVIEW --}}
            <div class="p-3 rounded" style="background:#f8f9fa; border:0.5px solid #dee2e6;">
                <div class="mb-1" style="font-size:12px; color:#4a6a80;">Google preview</div>
                <div id="preview-title" style="font-size:18px; color:#1a0dab;">{{ $place->meta_title ?: $place->title . ' — Earthlonis' }}</div>
                <div style="font-size:13px; color:#006621;">travellevel.com/{{ $place->country->slug }}/{{ $place->slug }}</div>
                <div id="preview-desc" style="font-size:13px; color:#545454;">{{ $place->meta_description ?: 'No description yet.' }}</div>
            </div>

            <div class="mt-4">
                <button type="submit" name="redirect_to" value="edit" class="btn btn-outline-primary">
                    Update
                </button>
                <button type="submit" name="redirect_to" value="index" class="btn btn-primary">
                    Update and Close
                </button>
            </div>

        </div>

    </div>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // STATUS SWITCH LIVE LABEL
    document.getElementById('is_published').addEventListener('change', function() {
        const box = document.getElementById('status-box');
        const label = document.getElementById('status-label');
        box.style.background = this.checked ? '#d1f2dd' : '#f1f3f5';
        label.innerHTML = (this.checked ? 'Published' : 'Draft')
            + ' <span class="text-muted" style="font-size:12px;">— visible on the public site only when published</span>';
    });

    // QUICK FACTS ROWS
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

    // KNOW BEFORE YOU GO ROWS
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

    // SOURCES ROWS
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

    // SEO COUNTERS + PREVIEW
    const titleInput = document.getElementById('meta-title-input');
    const descInput = document.getElementById('meta-desc-input');
    const titleCount = document.getElementById('meta-title-count');
    const descCount = document.getElementById('meta-desc-count');
    const previewTitle = document.getElementById('preview-title');
    const previewDesc = document.getElementById('preview-desc');

    function updateTitle() {
        const val = titleInput.value;
        const len = val.length;
        titleCount.textContent = len + ' chars';
        titleCount.style.color = len > 60 ? '#dc3545' : len >= 50 ? '#198754' : '#6c757d';
        previewTitle.textContent = val || '{{ $place->title }} — Earthlonis';
    }

    function updateDesc() {
        const val = descInput.value;
        const len = val.length;
        descCount.textContent = len + ' chars';
        descCount.style.color = len > 160 ? '#dc3545' : len >= 150 ? '#198754' : '#6c757d';
        previewDesc.textContent = val || 'No description yet.';
    }

    titleInput.addEventListener('input', updateTitle);
    descInput.addEventListener('input', updateDesc);
    updateTitle();
    updateDesc();

    // GALLERY DELETE
    const galleryContainer = document.getElementById('gallery-container');

    galleryContainer.addEventListener('click', function(e) {
        if (!e.target.classList.contains('delete-image')) return;
        if (!confirm('Delete this image?')) return;
        const button = e.target;
        fetch(button.dataset.url, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(() => document.getElementById('image-' + button.dataset.id).remove())
        .catch(() => alert('Delete failed'));
    });

    // GALLERY UPLOAD
    document.getElementById('gallery-upload-btn').addEventListener('click', function() {
        const input = document.getElementById('gallery-input');
        if (!input.files.length) return;

        const formData = new FormData();
        Array.from(input.files).forEach(file => formData.append('images[]', file));

        fetch('{{ route('backend.places.gallery.upload', $place) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            const noImagesText = document.getElementById('no-images-text');
            if (noImagesText) noImagesText.remove();
            data.images.forEach(image => {
                galleryContainer.insertAdjacentHTML('beforeend', `
                    <div class="col-md-3 mb-3" id="image-${image.id}">
                        <div class="card">
                            <img src="${image.url}" class="card-img-top" style="height:150px; object-fit:cover;">
                            <div class="card-body text-center p-2">
                                <button type="button" class="btn btn-sm btn-outline-danger delete-image"
                                        data-url="${image.delete_url}" data-id="${image.id}">Delete</button>
                            </div>
                        </div>
                    </div>
                `);
            });
            input.value = '';
        })
        .catch(() => alert('Upload failed'));
    });

});
</script>

@endsection
