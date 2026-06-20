<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Place;
use App\Models\Category;
use App\Models\Comment;

class HomeController extends Controller
{
    public function index()
    {
        $countries = Country::whereHas('places', function ($query) {
                $query->where('is_published', true);
            })
            ->withCount(['places' => function ($query) {
                $query->where('is_published', true);
            }])
            ->get();

        $trendingPlaces = Place::with(['country', 'category'])
            ->where('is_published', true)
            ->where('views_count', '>', 0)
            ->orderByDesc('views_count')
            ->take(4)
            ->get();

        $mostWantedPlaces = Place::with(['country', 'category'])
            ->where('is_published', true)
            ->where('want_to_go_count', '>', 0)
            ->orderByDesc('want_to_go_count')
            ->take(4)
            ->get();

        $newestPlaces = Place::with(['country', 'category'])
            ->where('is_published', true)
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::orderBy('name')->get();

        $placesCount        = Place::where('is_published', true)->count();
        $countriesCount     = $countries->count();
        $categoriesCount    = $categories->count();
        $commentsCount      = Comment::where('is_approved', true)->count();

        return view('frontend.home', compact(
            'countries',
            'trendingPlaces',
            'mostWantedPlaces',
            'newestPlaces',
            'categories',
            'placesCount',
            'countriesCount',
            'categoriesCount',
            'commentsCount'
        ));
    }

    public function show(Country $country, ?Category $category = null)
    {
        $country->load(['places' => function ($query) use ($category) {
            $query->where('is_published', true);

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }]);

        $categories = Category::whereHas('places', function ($query) use ($country) {
                $query->where('is_published', true)
                    ->where('country_id', $country->id);
            })
            ->orderBy('name')
            ->get();

        return view('frontend.countries.show', compact('country', 'category', 'categories'));
    }

    public function countries()
    {
        $countries = Country::whereHas('places', function ($query) {
                $query->where('is_published', true);
            })
            ->withCount(['places' => function ($query) {
                $query->where('is_published', true);
            }])
            ->orderBy('name')
            ->get();

        return view('frontend.countries.index', compact('countries'));
    }

    public function places(?Category $category = null)
    {
        $query = Place::with(['country', 'category'])
            ->where('is_published', true);

        if ($category) {
            $query->where('category_id', $category->id);
        }

        $places = $query->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('frontend.places.index', compact('places', 'categories', 'category'));
    }

    public function place(Country $country, Place $place)
    {
        abort_if(
            $place->country_id !== $country->id || !$place->is_published,
            404
        );

        return view('frontend.place', compact('place', 'country'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $places = collect();
        $countries = collect();

        if ($query) {
            $places = Place::with(['country', 'category'])
                ->where('is_published', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', '%'.$query.'%')
                    ->orWhere('description', 'like', '%'.$query.'%');
                })
                ->take(10)
                ->get();

            $countries = Country::whereHas('places', function ($q) {
                    $q->where('is_published', true);
                })
                ->where('name', 'like', '%'.$query.'%')
                ->take(5)
                ->get();
        }

        return view('frontend.search', compact('places', 'countries', 'query'));
    }
}