<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- HOME --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- COUNTRIES LIST --}}
    <url>
        <loc>{{ route('countries.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- PLACES LIST --}}
    <url>
        <loc>{{ route('places.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- COUNTRIES --}}
    @foreach($countries as $country)
    <url>
        <loc>{{ route('countries.show', $country) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- PLACES --}}
    @foreach($places as $place)
    <url>
        <loc>{{ route('places.show', $place->slug) }}</loc>
        <lastmod>{{ $place->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

</urlset>