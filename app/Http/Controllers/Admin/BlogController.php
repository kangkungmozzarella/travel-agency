<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\Category;
use App\Support\UniqueSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);

        return Inertia::render('Admin/Index', [
            'kind' => 'blogs',
            'items' => BlogResource::collection(Blog::with('category')->when($filters['search'] ?? null, fn ($query, $search) => $query->where('title', 'like', '%'.$search.'%'))->latest()->paginate(10)->withQueryString()),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/BlogForm', ['blog' => null, 'categories' => Category::orderBy('name')->get(['id', 'name'])]);
    }

    public function store(BlogRequest $request)
    {
        $data = $request->safe()->except('image');
        $data['image'] = $request->file('image')->store('blog/images', 'public');
        $data['slug'] = UniqueSlug::make(Blog::class, $request->title);
        Blog::create($data);

        return to_route('admin.blogs.index')->with('message', 'Artikel diterbitkan.');
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Admin/BlogForm', ['blog' => (new BlogResource($blog->load('category')))->resolve(), 'categories' => Category::orderBy('name')->get(['id', 'name'])]);
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $data = $request->safe()->except('image');
        $data['slug'] = UniqueSlug::make(Blog::class, $request->title, $blog);
        $oldImage = $blog->image;
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog/images', 'public');
        }
        $blog->update($data);
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('admin.blogs.index')->with('message', 'Artikel diperbarui.');
    }

    public function destroy(Blog $blog)
    {
        $image = $blog->image;
        $blog->delete();
        Storage::disk('public')->delete($image);

        return back()->with('message', 'Artikel dihapus.');
    }
}
