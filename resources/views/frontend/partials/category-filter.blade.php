{{-- Expects: $allUrl, $categories, $categoryUrl (closure: fn($cat) => url string), $activeCategory --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ $allUrl }}"
       class="text-decoration-none px-3 py-2 rounded-pill border small hover-pill {{ !$activeCategory ? 'is-active bg-primary text-white border-primary' : 'bg-white' }}">
        All
    </a>
    @foreach($categories as $cat)
        <a href="{{ $categoryUrl($cat) }}"
           class="text-decoration-none px-3 py-2 rounded-pill border small hover-pill {{ $activeCategory?->slug === $cat->slug ? 'is-active bg-primary text-white border-primary' : 'bg-white' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>
