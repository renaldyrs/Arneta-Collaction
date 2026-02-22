<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        // Redirect ke index — form sudah modal
        return redirect()->route('categories.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:categories,code',
            'name' => 'required|string|max:100',
        ]);

        $category = Category::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'category' => $category], 201);
        }

        Alert::success('Kategori berhasil ditambahkan.');
        return redirect()->route('categories.index');
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        if (request()->wantsJson()) {
            return response()->json($category);
        }
        return redirect()->route('categories.index');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:categories,code,' . $category->id,
            'name' => 'required|string|max:100',
        ]);

        $category->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'category' => $category->fresh()]);
        }

        Alert::success('Kategori berhasil diperbarui.');
        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        Alert::success('Kategori berhasil dihapus.');
        return redirect()->route('categories.index');
    }
}