<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Country;

class SitemapController extends Controller
{
    public function index()
    {
        $countries = Country::whereHas('places', function ($query) {
            $query->where('is_published', true);
        })->get();

        $places = Place::with('country')
            ->where('is_published', true)
            ->get();

        return response()
            ->view('frontend.sitemap', compact('countries', 'places'))
            ->header('Content-Type', 'text/xml');
    }
}
