<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Country;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::withCount('places')
            ->orderBy('name')
            ->paginate(20);

        return view('backend.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('countries', 'public');
        }

        Country::create($data);

        return redirect()
            ->route('backend.countries.index')
            ->with('success', 'Country created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        $placesCount = $country->places()->count();

        $categoriesCount = $country->places()
            ->distinct('category_id')
            ->count('category_id');

        return view('backend.countries.edit', compact(
            'country',
            'placesCount',
            'categoriesCount'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {

            if ($country->image) {
                Storage::disk('public')->delete($country->image);
            }

            $data['image'] = $request->file('image')
                ->store('countries', 'public');
        }

        $country->update($data);

        return redirect()
            ->route('backend.countries.index')
            ->with('success', 'Country updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        if ($country->image) {
            Storage::disk('public')->delete($country->image);
        }

        $country->delete();

        return redirect()
            ->route('backend.countries.index')
            ->with('success', 'Country deleted successfully.');
    }
}