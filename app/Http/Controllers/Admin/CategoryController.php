<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Support\UniqueSlug;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);
        $items = Category::withCount('blogs')->when($filters['search'] ?? null, fn ($query, $search) => $query->where('name', 'like', '%'.$search.'%'))->orderBy('name')->paginate(10)->withQueryString();

        return Inertia::render('Admin/Index', ['kind' => 'categories', 'items' => $items, 'filters' => $filters]);
    }

    public function create()
    {
        return Inertia::render('Admin/CategoryForm', ['category' => null]);
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated() + ['slug' => UniqueSlug::make(Category::class, $request->name)]);

        return to_route('admin.categories.index')->with('message', 'Kategori dibuat.');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Admin/CategoryForm', ['category' => $category->only('id', 'name')]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated() + ['slug' => UniqueSlug::make(Category::class, $request->name, $category)]);

        return to_route('admin.categories.index')->with('message', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->blogs()->exists()) {
            return back()->withErrors(['delete' => 'Kategori masih digunakan oleh artikel. Pindahkan artikel ke kategori lain terlebih dahulu.']);
        }
        $category->delete();

        return back()->with('message', 'Kategori dihapus.');
    }
}
