<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Gallery;
use App\Models\TravelPackage;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function store(GalleryRequest $request, TravelPackage $travel_package)
    {
        $travel_package->galleries()->create([
            'name' => $request->validated('name'),
            'images' => $request->file('images')->store('travel_package/gallery', 'public'),
        ]);

        return back()->with('message', 'Foto ditambahkan ke galeri.');
    }

    public function edit(TravelPackage $travel_package, Gallery $gallery)
    {
        $this->checkParent($travel_package, $gallery);

        return Inertia::render('Admin/GalleryForm', [
            'package' => $travel_package->only('id', 'location'),
            'gallery' => ['id' => $gallery->id, 'name' => $gallery->name, 'image_url' => Storage::url($gallery->images)],
        ]);
    }

    public function update(GalleryRequest $request, TravelPackage $travel_package, Gallery $gallery)
    {
        $this->checkParent($travel_package, $gallery);
        $data = ['name' => $request->validated('name')];
        $oldImage = $gallery->images;
        if ($request->hasFile('images')) {
            $data['images'] = $request->file('images')->store('travel_package/gallery', 'public');
        }
        $gallery->update($data);
        if ($request->hasFile('images')) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('admin.travel_packages.edit', $travel_package)->with('message', 'Foto diperbarui.');
    }

    public function destroy(TravelPackage $travel_package, Gallery $gallery)
    {
        $this->checkParent($travel_package, $gallery);
        $image = $gallery->images;
        $gallery->delete();
        Storage::disk('public')->delete($image);

        return back()->with('message', 'Foto dihapus.');
    }

    private function checkParent(TravelPackage $package, Gallery $gallery): void
    {
        abort_unless($gallery->travel_package_id === $package->id, 404);
    }
}
