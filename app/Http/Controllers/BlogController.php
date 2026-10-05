<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);

        return $this->listing(null, $filters);
    }

    public function category(Request $request, Category $category)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);

        return $this->listing($category, $filters);
    }

    private function listing(?Category $category, array $filters)
    {
        $blogs = Blog::with('category')
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('title', 'like', '%'.$search.'%'))
            ->latest()->paginate(9)->withQueryString();

        return Inertia::render('Blogs/Index', [
            'blogs' => BlogResource::collection($blogs),
            'categories' => Category::orderBy('name')->get(['id', 'name', 'slug']),
            'category' => $category?->only('id', 'name', 'slug'),
            'filters' => $filters,
        ]);
    }

    public function show(Blog $blog)
    {
        $blog->incrementReadCount();

        return Inertia::render('Blogs/Show', [
            'blog' => (new BlogResource($blog->load('category')))->resolve(),
            'related' => BlogResource::collection(Blog::with('category')->whereKeyNot($blog->id)->where('category_id', $blog->category_id)->latest()->limit(3)->get()),
        ]);
    }
}
