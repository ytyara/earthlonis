<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\PlaceController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\CommentController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PlaceController as FrontendPlaceController;
use App\Http\Controllers\Frontend\CommentController as FrontendCommentController;
use App\Http\Controllers\Frontend\SitemapController;


/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| BACKEND
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','admin'])
    ->prefix('backend')
    ->name('backend.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('places', PlaceController::class);
        Route::resource('categories', CategoryController::class);

        Route::post('places/{place}/gallery', [PlaceController::class, 'uploadGallery'])
            ->name('places.gallery.upload');

        Route::delete('gallery/{image}', [PlaceController::class, 'deleteGallery'])
            ->name('places.gallery.delete');

        Route::patch('places/{place}/toggle', [PlaceController::class, 'togglePublish'])
            ->name('places.toggle');

        Route::get('comments', [CommentController::class, 'index'])
            ->name('comments.index');

        Route::patch('comments/{comment}/approve', [CommentController::class, 'approve'])
            ->name('comments.approve');

        Route::patch('comments/{comment}/disapprove', [CommentController::class, 'disapprove'])
            ->name('comments.disapprove');

        Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
            ->name('comments.destroy');

        Route::resource('countries', CountryController::class);
    });


/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/countries', [HomeController::class, 'countries'])
    ->name('countries.index');

Route::get('/places', [HomeController::class, 'places'])
    ->name('places.index');

Route::get('/category/{category:slug}', [HomeController::class, 'places'])
    ->name('places.category');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');

Route::get('/search', [HomeController::class, 'search'])
    ->name('search');

Route::get('/country/{country:slug}', [HomeController::class, 'show'])
    ->name('countries.show');

Route::get('/country/{country:slug}/{category:slug}', [HomeController::class, 'show'])
    ->withoutScopedBindings()
    ->name('countries.category');

Route::get('/place/{place:slug}', [FrontendPlaceController::class, 'show'])
    ->name('places.show');

Route::post('/place/{place:slug}/comments', [FrontendCommentController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('comments.store');

Route::post('/place/{place:slug}/toggle-status', [FrontendPlaceController::class, 'toggleStatus'])
    ->middleware('throttle:20,1')
    ->name('places.toggle-status');