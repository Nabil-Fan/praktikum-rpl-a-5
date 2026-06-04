<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('foodListings')
            ->orderBy('name')
            ->get();

        // ID kategori yang sedang diedit (dari query string ?edit=id)
        $editing = $request->query('edit');

        return view('admin.categories.index', compact('categories', 'editing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:categories,name'],
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        Category::create(['name' => $request->name]);

        return back()->with('success', "Kategori \"{$request->name}\" berhasil ditambahkan.");
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:categories,name,' . $category->id],
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        $old = $category->name;
        $category->update(['name' => $request->name]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$old}\" diubah menjadi \"{$request->name}\".");
    }

    public function destroy(Category $category)
    {
        $activeCount = $category->foodListings()
            ->whereNull('deleted_at')
            ->count();

        if ($activeCount > 0) {
            return back()->with('error', "Kategori \"{$category->name}\" tidak bisa dihapus karena masih dipakai oleh {$activeCount} listing aktif.");
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }
}