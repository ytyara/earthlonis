{{-- Expects: $place, and optionally $metricIcon + $metricValue for the top-right overlay badge --}}
<div class="col-6 col-md-3">
    <a href="{{ route('places.show', $place) }}" class="text-decoration-none">
        <div class="card border h-100 hover-lift">

            <div class="thumb-md overflow-hidden bg-placeholder position-relative">
                @if($place->image)
                    <img src="{{ asset('storage/'.$place->image) }}"
                         alt="{{ $place->title }}"
                         class="w-100 h-100 object-fit-cover d-block">
                @endif

                @isset($metricIcon)
                    <span class="position-absolute top-0 end-0 m-2 badge bg-white bg-opacity-75 text-dark fw-medium fs-8 d-flex align-items-center gap-1">
                        <i class="bi {{ $metricIcon }} {{ $metricIconClass ?? '' }}"></i> {{ $metricValue }}
                    </span>
                @endisset

                @if($place->category)
                    <span class="position-absolute top-0 start-0 m-2 badge bg-white bg-opacity-75 text-dark fw-medium fs-8">
                        {{ $place->category->name }}
                    </span>
                @endif
            </div>

            <div class="card-body py-3 px-3">
                <div class="fw-medium text-dark mb-1 small">{{ $place->title }}</div>
                <div class="text-muted fs-7">{{ $place->country->name }}</div>
            </div>

        </div>
    </a>
</div>
