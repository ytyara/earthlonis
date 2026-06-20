# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Laravel 12 travel blog: a public-facing site listing countries and places, with an admin
backend for managing content. Scaffolded with Laravel Breeze (session-based auth + Blade/Alpine
views, no SPA framework). Frontend uses Tailwind + Alpine.js, built via Vite.

## Commands

Local dev runs through Laravel Sail (Docker). `compose.yaml` defines `mysql`, `redis`,
`meilisearch`, `mailpit`, and `selenium` alongside the app container.

```bash
./vendor/bin/sail up -d          # start the Docker stack
./vendor/bin/sail composer dev   # serve + queue listener + pail logs + vite, all at once (composer.json "dev" script)
```

If running PHP/Node directly on the host instead of via Sail:

```bash
php artisan serve
npm run dev                      # Vite dev server
npm run build                    # production asset build
```

Tests use Pest (with `pestphp/pest-plugin-laravel`), not raw PHPUnit syntax:

```bash
composer test                    # clears config cache, then runs the suite
php artisan test                 # equivalent, without the config:clear step
php artisan test --filter=AuthenticationTest
php artisan test tests/Feature/Auth/AuthenticationTest.php
```

`tests/Pest.php` binds `Tests\TestCase` with `RefreshDatabase` for everything under `Feature`.

Other useful Artisan commands:

```bash
php artisan migrate
php artisan db:seed              # runs CountrySeeder (loads database/data/countries.json) + a test user
php artisan pint                 # code style (Laravel Pint)
```

## Styling

Markup uses **Bootstrap 5 only** (loaded via CDN in
[layouts/front.blade.php](resources/views/layouts/front.blade.php) and
[layouts/backend.blade.php](resources/views/layouts/backend.blade.php), plus Bootstrap Icons).
Do not introduce Tailwind utility classes, custom CSS frameworks, or ad-hoc styling systems in
new views — even though Tailwind is present in `package.json`/Vite config from the original
Breeze scaffold, it is not used for layout/styling and should be treated as dead weight, not a
second design system. Stick to Bootstrap classes/components for any new markup. Custom styles
will be introduced deliberately once the project reaches a more final stage — don't anticipate
that work now.

## Architecture

**Frontend vs Backend split.** Controllers are namespaced `App\Http\Controllers\Frontend` and
`App\Http\Controllers\Backend`, both operating on the same Eloquent models. Backend routes live
under the `backend.` name/prefix in [routes/web.php](routes/web.php) and are gated by the `auth`
+ `admin` middleware group; frontend routes are public. The `admin` alias maps to
[AdminMiddleware](app/Http/Middleware/AdminMiddleware.php), which checks
`auth()->user()->role === 'admin'` (also exposed as `User::isAdmin()`). There is no policy/gate
layer — authorization is this single role check.

**Content model.** `Country` hasMany `Place`; `Place` belongsTo `Country` and `Category`, hasMany
`PlaceImage` (gallery), hasMany `Comment`. Publishing is controlled by `Place::is_published`,
toggled via a dedicated `PATCH backend/places/{place}/toggle` route rather than going through the
normal update flow — frontend queries always filter `where('is_published', true)`.

**Comments are moderated and threaded.** `Comment` is self-referential (`parent_id` /
`replies()`); top-level comments load with `Place::comments()`, which already filters to
`whereNull('parent_id')->where('is_approved', true)` and eager-loads `replies`. New comments
submitted from the frontend ([Frontend/CommentController](app/Http/Controllers/Frontend/CommentController.php))
are always created with `is_approved = false`; backend
[Backend/CommentController](app/Http/Controllers/Backend/CommentController.php) provides
approve/disapprove/destroy actions.

**File uploads always target the `public` disk explicitly** (`Storage::disk('public')`,
`$file->store($dir, 'public')`), independent of the `FILESYSTEM_DISK` env value. Conventions:
`countries/`, `places/`, `places/gallery/`, and `editor/` (used by an image-uploading rich text
editor in the place form, see `PlaceController::uploadImage`). `public/storage` is a symlink to
`storage/app/public` — if it's missing, run `php artisan storage:link`. Deleting a `Place` or
`Country` also deletes its associated image file(s) from disk, not just the DB row.

**Per-country maps use static GeoJSON files.** `public/geojson/{ISO_CODE}.json` (one file per
country, keyed by `Country.iso_code`) is referenced directly from
[resources/views/frontend/countries/show.blade.php](resources/views/frontend/countries/show.blade.php)
for rendering country boundaries — there's no API/controller behind this, it's static lookup by
ISO code on the client side.

**Slugs are derived, not user-editable.** Both `Place` and `Country` compute `slug` server-side
from `title`/`name` via `Str::slug()` on every create/update; routes use route-model binding on
slug (`{country:slug}`, `{place:slug}`).

**SEO surface:** `Place` has `meta_title`/`meta_description` fields, and
[Frontend/SitemapController](app/Http/Controllers/Frontend/SitemapController.php) serves
`/sitemap.xml`.
