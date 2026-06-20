<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('places')
            ->orderBy('name')
            ->paginate(10);

        return view('backend.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return redirect()
            ->route('backend.categories.index')
            ->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
        $placesCount = $category->places()->count();

        $countriesCount = $category->places()
            ->distinct('country_id')
            ->count('country_id');

        return view('backend.categories.edit', compact(
            'category',
            'placesCount',
            'countriesCount'
        ));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()
            ->route('backend.categories.index')
            ->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('backend.categories.index')
            ->with('success', 'Category deleted!');
    }
}