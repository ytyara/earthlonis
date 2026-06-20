<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Place;

class PlaceController extends Controller
{
    public function show(Place $place, GeocodingService $geocoding)
    {
        abort_if(!$place->is_published, 404);

        // Raw query builder so the view counter doesn't touch updated_at.
        DB::table('places')->where('id', $place->id)->increment('views_count');

        $country = $place->country;

        $location = null;

        if ($place->latitude && $place->longitude) {
            $location = $geocoding->reverseGeocode($place->latitude, $place->longitude);
        }

        $nearbyPlaces = Place::with('country')
            ->where('is_published', true)
            ->where('id', '!=', $place->id)
            ->where('country_id', $place->country_id)
            ->where('category_id', $place->category_id)
            ->take(5)
            ->get();

        return view('frontend.places.show', compact('place', 'country', 'nearbyPlaces', 'location'));
    }

    public function toggleStatus(Request $request, Place $place)
    {
        $data = $request->validate([
            'type'   => 'required|in:been_here,want_to_go',
            'active' => 'required|boolean',
        ]);

        $column = $data['type'] . '_count';

        // Raw query builder so toggling Been Here/Want to Go doesn't touch updated_at.
        if ($data['active']) {
            DB::table('places')->where('id', $place->id)->increment($column);
        } else {
            DB::table('places')->where('id', $place->id)->where($column, '>', 0)->decrement($column);
        }

        $place->refresh();

        return response()->json([
            'been_here_count'  => $place->been_here_count,
            'want_to_go_count' => $place->want_to_go_count,
        ]);
    }
}
