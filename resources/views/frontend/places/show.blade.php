@extends('layouts.front')

@section('title', $place->meta_title ?: $place->title . ', ' . $country->name . ' — Earthlonis')
@section('description', $place->meta_description ?: ($place->tagline ?: Str::limit(strip_tags($place->description), 160)))
@section('og_title', $place->meta_title ?: $place->title . ', ' . $country->name . ' — Earthlonis')
@section('og_description', $place->meta_description ?: ($place->tagline ?: Str::limit(strip_tags($place->description), 160)))
@section('og_image', $place->image ? asset('storage/' . $place->image) : asset('img/og-default.jpg'))

@if($place->latitude && $place->longitude)
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
@endpush
@endif

@push('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $country->name, 'item' => route('countries.show', $country)],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $place->title, 'item' => route('places.show', $place)],
    ],
]) !!}
</script>
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@@context' => 'https://schema.org',
    '@type' => 'TouristAttraction',
    'name' => $place->title,
    'url' => route('places.show', $place),
    'description' => $place->meta_description ?: ($place->tagline ?: Str::limit(strip_tags($place->description), 160)),
    'image' => $place->image ? asset('storage/' . $place->image) : null,
    'address' => [
        '@type' => 'PostalAddress',
        'addressCountry' => $country->name,
    ],
    'geo' => ($place->latitude && $place->longitude) ? [
        '@type' => 'GeoCoordinates',
        'latitude' => (float) $place->latitude,
        'longitude' => (float) $place->longitude,
    ] : null,
])) !!}
</script>
@endpush

@section('content')

{{-- HERO IMAGE --}}
@if($place->image)
    <div class="thumb-xl overflow-hidden bg-placeholder">
        <img src="{{ asset('storage/'.$place->image) }}"
             alt="{{ $place->title }}"
             class="w-100 h-100 object-fit-cover d-block">
    </div>
@endif

{{-- CONTENT --}}
<div class="container py-5">

    {{-- BREADCRUMB --}}
    <nav class="mb-4 small text-secondary">
        <a href="{{ url('/') }}" class="text-decoration-none text-primary">Home</a>
        <span class="mx-2">·</span>
        <a href="{{ route('countries.show', $country) }}" class="text-decoration-none text-primary">{{ $country->name }}</a>
        <span class="mx-2">·</span>
        <span>{{ $place->title }}</span>
    </nav>

    <div class="row g-5">

        {{-- MAIN --}}
        <div class="col-md-8">

            <h1 class="mb-2 fs-2 fw-medium text-dark">
                {{ $place->title }}
            </h1>

            @if($place->tagline)
                <p class="mb-3 text-secondary lh-base">
                    {{ $place->tagline }}
                </p>
            @endif

            <div class="d-flex align-items-center gap-2 mb-2 small text-secondary">
                <a href="{{ route('countries.show', $country) }}"
                   class="text-decoration-none text-secondary">{{ $country->name }}</a>
                @if($place->category)
                    <span class="dot-sep"></span>
                    <a href="{{ route('places.category', $place->category->slug) }}"
                       class="text-decoration-none text-secondary">{{ $place->category->name }}</a>
                @endif
            </div>

            <div class="mb-4 fs-7 text-muted-soft">
                Published {{ $place->created_at->format('M j, Y') }}
                @if(abs($place->updated_at->diffInMinutes($place->created_at)) > 1)
                    · Updated {{ $place->updated_at->format('M j, Y') }}
                @endif
            </div>

            {{-- GALLERY --}}
            @if($place->images->count())
                <div class="row g-2 mb-5">
                    @foreach($place->images as $img)
                        <div class="col-6 col-md-4">
                            <a href="{{ asset('storage/'.$img->image) }}"
                               class="glightbox"
                               data-gallery="place-gallery">
                                <div class="thumb-md overflow-hidden rounded-2 bg-placeholder">
                                    <img src="{{ asset('storage/'.$img->image) }}"
                                         alt=""
                                         class="w-100 h-100 object-fit-cover d-block">
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- KNOW BEFORE YOU GO --}}
            @if(!empty($place->know_before_you_go))
                <div class="card border mb-4">
                    <div class="card-body">
                        <h2 class="mb-3 fs-6 fw-medium text-dark">Know Before You Go</h2>
                        @foreach($place->know_before_you_go as $tip)
                            <div class="d-flex gap-2 mb-2 small">
                                <span class="fw-medium text-dark mw-label">{{ $tip['label'] }}</span>
                                <span>{{ $tip['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- QUICK FACTS --}}
            @if(!empty($place->quick_facts))
                <div class="row g-2 mb-4">
                    @foreach($place->quick_facts as $fact)
                        <div class="col-{{ max(2, intdiv(12, min(count($place->quick_facts), 4))) }}">
                            <div class="card border h-100 text-center p-3">
                                <div class="fw-medium text-dark">{{ $fact['value'] }}</div>
                                <div class="text-muted text-uppercase mt-1 fs-8 tracked">{{ $fact['label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- DESCRIPTION --}}
            <div class="lh-lg">
                {!! $place->description !!}
            </div>

            {{-- SOURCES --}}
            @if(!empty($place->sources))
                <div class="mt-4 small text-secondary">
                    <div class="text-muted mb-1 fs-7 text-uppercase tracked">Sources</div>
                    <ol class="ps-3 mb-0">
                        @foreach($place->sources as $url)
                            <li class="mb-1">
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="text-decoration-none text-primary">
                                    {{ $url }}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

{{-- COMMENTS --}}
<hr class="my-5" id="comments">

<h2 class="mb-4 fs-5 fw-medium">
    Comments
    @if($place->comments->count())
        <span class="text-muted fw-normal">({{ $place->comments->count() }})</span>
    @endif
</h2>

{{-- SUCCESS MESSAGE --}}
@if(session('success'))
    <div class="alert alert-success mb-4 small">
        {{ session('success') }}
    </div>
@endif

{{-- COMMENT FORM --}}
<div class="card border p-4 mb-5">
    <h5 class="mb-3 fs-6 fw-medium">Leave a comment</h5>

    <div id="comment-success" class="alert alert-success mb-3 small d-none"></div>

    <form id="comment-form">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Your name</label>
            <input type="text"
                   name="name"
                   id="comment-name"
                   class="form-control"
                   placeholder="John Doe">
            <div class="invalid-feedback" id="error-name"></div>
        </div>
        <div class="mb-3">
            <label class="form-label small">Comment</label>
            <textarea name="body"
                      id="comment-body"
                      rows="4"
                      class="form-control"
                      placeholder="Share your experience..."></textarea>
            <div class="invalid-feedback" id="error-body"></div>
        </div>
        <button type="submit" id="comment-submit" class="btn btn-primary btn-sm px-4">
            Submit
        </button>
    </form>
</div>

{{-- COMMENTS LIST --}}
@forelse($place->comments as $comment)
    <div class="mb-4" id="comment-{{ $comment->id }}">
        <div class="d-flex gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 avatar-md bg-light text-primary fw-medium">
                {{ strtoupper(substr($comment->name, 0, 1)) }}
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-baseline gap-2 mb-1">
                    <span class="small fw-medium text-dark">{{ $comment->name }}</span>
                    <span class="fs-7 text-secondary">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="small mb-2">{{ $comment->body }}</p>
                    <button class="btn btn-link p-0 toggle-reply fs-7 text-primary text-decoration-none"
                            data-parent-id="{{ $comment->id }}"
                            data-name="">
                        Reply
                    </button>

                {{-- REPLY FORM --}}
                <div class="reply-form mt-3 d-none" id="reply-form-{{ $comment->id }}">
                    <div id="reply-success-{{ $comment->id }}" class="alert alert-success mb-2 fs-7 d-none"></div>
                    <div class="mb-2">
                        <input type="text"
                            class="form-control form-control-sm reply-name"
                            placeholder="Your name">
                    </div>
                    <div class="mb-2">
                        <textarea rows="3"
                                class="form-control form-control-sm reply-body"
                                placeholder="Your reply..."></textarea>
                    </div>
                    <button class="btn btn-primary btn-sm px-3 send-reply"
                            data-id="{{ $comment->id }}"
                            data-url="{{ route('comments.store', $place) }}"
                            data-parent="{{ $comment->id }}">
                        Send reply
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-link cancel-reply fs-7 text-secondary"
                            data-id="{{ $comment->id }}">
                        Cancel
                    </button>
                </div>

                {{-- REPLIES --}}
                @if($comment->replies->count())
                    <div class="mt-3 ps-3 border-start border-2 border-light">
                        @foreach($comment->replies as $reply)
                            @if($reply->is_approved)
                                <div class="d-flex gap-3 mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 avatar-sm bg-light text-primary fw-medium fs-7">
                                        {{ strtoupper(substr($reply->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-baseline gap-2 mb-1">
                                            <span class="fs-7 fw-medium text-dark">{{ $reply->name }}</span>
                                            <span class="fs-8 text-secondary">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="fs-7 mb-2">{{ $reply->body }}</p>
                                        <button class="btn btn-link p-0 toggle-reply fs-7 text-primary text-decoration-none"
                                                data-parent-id="{{ $comment->id }}"
                                                data-name="{{ $reply->name }}">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
    <hr class="border-faint">
@empty
    <p class="text-muted small">No comments yet. Be the first!</p>
@endforelse

        </div>

        {{-- SIDEBAR --}}
        <div class="col-md-4">
            <div class="card border p-3 small sticky-sidebar">

                {{-- BEEN HERE / WANT TO GO --}}
                <div class="d-flex gap-2 mb-3" id="place-status-buttons" data-place-slug="{{ $place->slug }}">
                    <button type="button"
                            id="been-here-btn"
                            class="btn flex-fill text-center p-3 status-btn">
                        <i class="bi bi-flag-fill d-block mb-1 status-icon fs-5" id="been-here-icon"></i>
                        <div class="fw-medium text-dark" id="been-here-count">{{ $place->been_here_count }}</div>
                        <div class="text-muted text-uppercase fs-8 tracked">Been Here</div>
                    </button>

                    <button type="button"
                            id="want-to-go-btn"
                            class="btn flex-fill text-center p-3 status-btn">
                        <i class="bi bi-star-fill d-block mb-1 status-icon fs-5" id="want-to-go-icon"></i>
                        <div class="fw-medium text-dark" id="want-to-go-count">{{ $place->want_to_go_count }}</div>
                        <div class="text-muted text-uppercase fs-8 tracked">Want to Go</div>
                    </button>
                </div>

                {{-- SHARE --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="text-muted text-uppercase fs-8 tracked">Share</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                       target="_blank" rel="noopener"
                       class="d-flex align-items-center justify-content-center rounded-circle icon-btn bg-light text-primary">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($place->title) }}"
                       target="_blank" rel="noopener"
                       class="d-flex align-items-center justify-content-center rounded-circle icon-btn bg-light text-primary">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <button type="button" id="copy-link-btn"
                            class="d-flex align-items-center justify-content-center rounded-circle icon-btn bg-light text-primary border-0">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                    <span id="copy-link-feedback" class="text-success fs-7 d-none">Copied!</span>
                </div>

                @if($place->latitude && $place->longitude)
                    <div class="mb-3">
                        @if($location && ($location['city'] || $location['region'] || $location['country']))
                            <div class="text-muted mb-1">Location</div>
                            <div class="fw-medium mb-2">
                                {{ implode(', ', array_filter([$location['city'], $location['region'], $location['country']])) }}
                            </div>
                        @endif
                        <div id="place-map" class="map-sm rounded-3 overflow-hidden border mb-2"></div>
                        <div class="text-muted mb-1">Coordinates</div>
                        <div class="fw-medium">{{ rtrim(rtrim($place->latitude, "0"), ".") }}, {{ rtrim(rtrim($place->longitude, "0"), ".") }}</div>
                    </div>
                @endif

                @if($nearbyPlaces->count())
                    <hr class="my-3">
                    <div class="text-muted mb-2">Similar Places</div>
                    @foreach($nearbyPlaces as $similar)
                        <a href="{{ route('places.show', $similar->slug) }}"
                           class="text-decoration-none d-flex align-items-center gap-2 mb-2">
                            <div class="thumb-xs rounded-2 overflow-hidden bg-placeholder flex-shrink-0">
                                @if($similar->image)
                                    <img src="{{ asset('storage/'.$similar->image) }}"
                                         alt="{{ $similar->title }}"
                                         class="w-100 h-100 object-fit-cover d-block">
                                @endif
                            </div>
                            <span class="text-dark">{{ $similar->title }}</span>
                        </a>
                    @endforeach
                @endif

                <hr class="my-3">
                <a href="{{ route('countries.show', $country) }}"
                   class="text-decoration-none text-primary">
                    ← Back to {{ $country->name }}
                </a>
            </div>
        </div>

    </div>

</div>

@endsection

@if($place->latitude && $place->longitude)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('place-map', {
        zoomControl: true,
        scrollWheelZoom: false,
        dragging: false,
        doubleClickZoom: true,
        minZoom: 5,
        maxBounds: [[-85, -180], [85, 180]],
        maxBoundsViscosity: 1.0,
    }).setView([{{ $place->latitude }}, {{ $place->longitude }}], 13);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap © CARTO',
        noWrap: true,
    }).addTo(map);

    L.marker([{{ $place->latitude }}, {{ $place->longitude }}])
        .addTo(map)
        .bindPopup({{ Js::from($place->title) }});
});
</script>
@endpush
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // COPY LINK
    const copyBtn = document.getElementById('copy-link-btn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                const feedback = document.getElementById('copy-link-feedback');
                feedback.classList.remove('d-none');
                setTimeout(() => feedback.classList.add('d-none'), 2000);
            });
        });
    }

    // BEEN HERE / WANT TO GO
    const statusContainer = document.getElementById('place-status-buttons');
    if (statusContainer) {
        const placeSlug = statusContainer.dataset.placeSlug;

        const types = {
            'been_here': { btn: 'been-here-btn', icon: 'been-here-icon', count: 'been-here-count' },
            'want_to_go': { btn: 'want-to-go-btn', icon: 'want-to-go-icon', count: 'want-to-go-count' },
        };

        function storageKey(type) {
            return 'place-' + placeSlug + '-' + type;
        }

        function applyState(type, active) {
            document.getElementById(types[type].btn).classList.toggle('is-active', active);
        }

        Object.keys(types).forEach(type => {
            applyState(type, localStorage.getItem(storageKey(type)) === '1');

            document.getElementById(types[type].btn).addEventListener('click', function() {
                const isActive = localStorage.getItem(storageKey(type)) === '1';
                const nextActive = !isActive;

                fetch(`{{ route('places.toggle-status', $place) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ type: type, active: nextActive })
                })
                .then(r => r.json())
                .then(data => {
                    localStorage.setItem(storageKey(type), nextActive ? '1' : '0');
                    applyState(type, nextActive);
                    document.getElementById(types.been_here.count).textContent = data.been_here_count;
                    document.getElementById(types.want_to_go.count).textContent = data.want_to_go_count;
                })
                .catch(() => {});
            });
        });
    }

    // REPLY TOGGLE
    document.querySelectorAll('.toggle-reply').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.parentId;
            const name = btn.dataset.name;
            const form = document.getElementById('reply-form-' + id);
            const bodyField = form.querySelector('.reply-body');

            document.querySelectorAll('.reply-form').forEach(f => f.classList.add('d-none'));

            form.classList.remove('d-none');

            if (name) {
                bodyField.value = '@' + name + ' ';
            } else {
                bodyField.value = '';
            }

            bodyField.focus();
        });
    });

    document.querySelectorAll('.cancel-reply').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('reply-form-' + btn.dataset.id).classList.add('d-none');
        });
    });

    // COMMENT FORM
    const commentForm = document.getElementById('comment-form');
    if (!commentForm) return;

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('comment-submit');
        const successBox = document.getElementById('comment-success');

        document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        btn.disabled = true;
        btn.textContent = 'Sending...';

        fetch('{{ route('comments.store', $place) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: document.getElementById('comment-name').value,
                body: document.getElementById('comment-body').value,
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                successBox.textContent = data.message;
                successBox.classList.remove('d-none');
                document.getElementById('comment-name').value = '';
                document.getElementById('comment-body').value = '';
            }
        })
        .catch(() => {
            successBox.textContent = 'Something went wrong. Please try again.';
            successBox.classList.remove('d-none');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Submit';
        });
    });

    // SEND REPLY
    document.querySelectorAll('.send-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const url = this.dataset.url;
            const parentId = this.dataset.parent;
            const container = document.getElementById('reply-form-' + id);
            const name = container.querySelector('.reply-name').value;
            const body = container.querySelector('.reply-body').value;
            const successBox = document.getElementById('reply-success-' + id);

            btn.disabled = true;
            btn.textContent = 'Sending...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    body: body,
                    parent_id: parentId,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    successBox.textContent = data.message;
                    successBox.classList.remove('d-none');
                    container.querySelector('.reply-name').value = '';
                    container.querySelector('.reply-body').value = '';
                }
            })
            .catch(() => {
                successBox.textContent = 'Something went wrong.';
                successBox.classList.remove('d-none');
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Send reply';
            });
        });
    });

});
</script>
@endpush