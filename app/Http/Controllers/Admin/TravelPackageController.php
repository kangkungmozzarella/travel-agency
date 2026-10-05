<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TravelPackageRequest;
use App\Http\Resources\TravelPackageResource;
use App\Models\Booking;
use App\Models\TravelPackage;
use App\Support\UniqueSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TravelPackageController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);
        $packages = TravelPackage::with('galleries')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('location', 'like', '%'.$search.'%'))
            ->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Index', [
            'kind' => 'travel_packages',
            'items' => TravelPackageResource::collection($packages),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/PackageForm', ['package' => null]);
    }

    public function store(TravelPackageRequest $request)
    {
        $package = TravelPackage::create($request->validated() + ['slug' => UniqueSlug::make(TravelPackage::class, $request->location)]);

        return to_route('admin.travel_packages.edit', $package)->with('message', 'Paket dibuat. Tambahkan foto untuk melengkapi destinasi.');
    }

    public function edit(TravelPackage $travel_package)
    {
        return Inertia::render('Admin/PackageForm', ['package' => (new TravelPackageResource($travel_package->load('galleries')))->resolve()]);
    }

    public function update(TravelPackageRequest $request, TravelPackage $travel_package)
    {
        $travel_package->update($request->validated() + ['slug' => UniqueSlug::make(TravelPackage::class, $request->location, $travel_package)]);

        return back()->with('message', 'Perubahan paket disimpan.');
    }

    public function destroy(TravelPackage $travel_package)
    {
        $images = $travel_package->galleries()->pluck('images');

        if (Booking::where('travel_package_id', $travel_package->id)->exists()) {
            return back()->withErrors(['delete' => 'Paket masih memiliki booking. Pertahankan paket agar riwayat booking tidak hilang.']);
        }

        $travel_package->delete();
        Storage::disk('public')->delete($images->all());

        return back()->with('message', 'Paket dan foto galerinya dihapus.');
    }
}
