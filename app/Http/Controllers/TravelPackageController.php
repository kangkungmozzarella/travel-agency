<?php

namespace App\Http\Controllers;

use App\Http\Resources\TravelPackageResource;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TravelPackageController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'in:newest,price_asc,price_desc'],
        ]);
        $query = TravelPackage::with('galleries')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('location', 'like', '%'.$search.'%'))
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->where('type', $type));

        match ($filters['sort'] ?? 'newest') {
            'price_asc' => $query->orderBy('price')->orderBy('id'),
            'price_desc' => $query->orderByDesc('price')->orderBy('id'),
            default => $query->latest()->orderBy('id'),
        };

        return Inertia::render('Packages/Index', [
            'packages' => TravelPackageResource::collection($query->paginate(9)->withQueryString()),
            'types' => TravelPackage::distinct()->orderBy('type')->pluck('type'),
            'filters' => (object) $filters,
        ]);
    }

    public function show(TravelPackage $travel_package)
    {
        return Inertia::render('Packages/Show', [
            'package' => (new TravelPackageResource($travel_package->load('galleries')))->resolve(),
            'related' => TravelPackageResource::collection(TravelPackage::with('galleries')->whereKeyNot($travel_package->id)->latest()->limit(3)->get()),
        ]);
    }
}
