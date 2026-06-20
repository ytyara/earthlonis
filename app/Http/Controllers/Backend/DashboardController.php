<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Country;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $places     = Place::count();
        $countries  = Country::whereHas('places')->count();
        $categories = Category::count();
        $totalViews = Place::sum('views_count');

        $latestPlaces = Place::with('country')
            ->latest()
            ->take(5)
            ->get();

        $mostViewedPlaces = Place::with('country')
            ->where('views_count', '>', 0)
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        return view('backend.dashboard', compact(
            'places',
            'countries',
            'categories',
            'totalViews',
            'latestPlaces',
            'mostViewedPlaces'
        ));
    }
}