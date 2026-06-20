<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use Illuminate\Pagination\Paginator;

use App\Models\Country;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        View::composer('*', function ($view) {
            $menuCountries = Country::has('places')
                ->orderBy('name')
                ->get();

            $view->with('menuCountries', $menuCountries);
        });
    }
}
