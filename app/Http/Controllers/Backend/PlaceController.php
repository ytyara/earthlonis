<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Place;
use App\Models\Country;
use App\Models\Category;
use App\Models\PlaceImage;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Place::with(['country', 'category']);

        if ($request->get('sort') === 'views') {
            $query->orderByDesc('views_count');
        } else {
            $query->latest();
        }

        $places = $query->paginate(10)->withQueryString();

        return view('backend.places.index', compact('places'));
    }

    public function create()
    {
        $countries  = Country::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.places.create', compact('countries', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'country_id'        => 'required|exists:countries,id',
            'category_id'       => 'nullable|exists:categories,id',
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'quick_facts'       => 'nullable|array',
            'quick_facts.*.label' => 'nullable|string|max:100',
            'quick_facts.*.value' => 'nullable|string|max:255',
            'know_before_you_go'       => 'nullable|array',
            'know_before_you_go.*.label' => 'nullable|string|max:100',
            'know_before_you_go.*.value' => 'nullable|string|max:255',
            'sources'           => 'nullable|array',
            'sources.*'         => 'nullable|url|max:500',
            'tagline'           => 'nullable|string|max:255',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'image'             => 'nullable|image|max:2048',
            'meta_title'        => 'nullable|string|max:80',
            'meta_description'  => 'nullable|string|max:200',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['quick_facts'] = $this->filterLabelValueRows($data['quick_facts'] ?? []);
        $data['know_before_you_go'] = $this->filterLabelValueRows($data['know_before_you_go'] ?? []);
        $data['sources'] = $this->filterSources($data['sources'] ?? []);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('places', 'public');
        }

        Place::create($data);

        return redirect()->route('backend.places.index')
            ->with('success', 'Place created!');
    }

    public function edit(Place $place)
    {
        $place->load('images');

        $countries  = Country::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('backend.places.edit', compact('place', 'countries', 'categories'));
    }

    public function update(Request $request, Place $place)
    {
        $data = $request->validate([
            'country_id'       => 'required|exists:countries,id',
            'category_id'      => 'nullable|exists:categories,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'quick_facts'      => 'nullable|array',
            'quick_facts.*.label' => 'nullable|string|max:100',
            'quick_facts.*.value' => 'nullable|string|max:255',
            'know_before_you_go'      => 'nullable|array',
            'know_before_you_go.*.label' => 'nullable|string|max:100',
            'know_before_you_go.*.value' => 'nullable|string|max:255',
            'sources'          => 'nullable|array',
            'sources.*'        => 'nullable|url|max:500',
            'tagline'          => 'nullable|string|max:255',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'image'            => 'nullable|image|max:2048',
            'meta_title'       => 'nullable|string|max:80',
            'meta_description' => 'nullable|string|max:200',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['quick_facts'] = $this->filterLabelValueRows($data['quick_facts'] ?? []);
        $data['know_before_you_go'] = $this->filterLabelValueRows($data['know_before_you_go'] ?? []);
        $data['sources'] = $this->filterSources($data['sources'] ?? []);

        if ($request->hasFile('image')) {
            if ($place->image) {
                Storage::disk('public')->delete($place->image);
            }
            $data['image'] = $request->file('image')->store('places', 'public');
        }

        $data['is_published'] = $request->boolean('is_published');

        $place->update($data);

        if ($request->input('redirect_to') === 'edit') {
            return redirect()->route('backend.places.edit', $place)
                ->with('success', 'Place updated!');
        }

        return redirect()->route('backend.places.index')
            ->with('success', 'Place updated!');
    }

    public function destroy(Place $place)
    {
        if ($place->image) {
            Storage::disk('public')->delete($place->image);
        }

        $place->images->each(function ($image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        });

        $place->delete();

        return redirect()->route('backend.places.index')
            ->with('success', 'Place deleted!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:4096'
        ]);

        $path = $request->file('file')->store('editor', 'public');

        return response()->json([
            'location' => asset('storage/' . $path)
        ]);
    }

    public function uploadGallery(Request $request, Place $place)
    {
        $request->validate([
            'images.*' => 'required|image|max:2048'
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('places/gallery', 'public');

            $newImage = $place->images()->create([
                'image' => $path
            ]);

            $uploadedImages[] = [
                'id'         => $newImage->id,
                'url'        => asset('storage/' . $path),
                'delete_url' => route('backend.places.gallery.delete', $newImage)
            ];
        }

        if ($request->expectsJson()) {
            return response()->json(['images' => $uploadedImages]);
        }

        return back()->with('success', 'Images uploaded!');
    }

    public function deleteGallery(PlaceImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function togglePublish(Place $place)
    {
        $place->is_published = !$place->is_published;
        $place->save();

        return back()->with('success', 'Status updated!');
    }

    private function filterLabelValueRows(array $rows): array
    {
        return array_values(array_filter($rows, function ($row) {
            return trim($row['label'] ?? '') !== '' || trim($row['value'] ?? '') !== '';
        }));
    }

    private function filterSources(array $sources): array
    {
        return array_values(array_filter(array_map('trim', $sources)));
    }
}