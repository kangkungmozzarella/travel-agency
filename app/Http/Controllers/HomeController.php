<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResource;
use App\Http\Resources\TravelPackageResource;
use App\Models\Blog;
use App\Models\TravelPackage;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'packages' => TravelPackageResource::collection(TravelPackage::with('galleries')->latest()->limit(4)->get()),
            'blogs' => BlogResource::collection(Blog::with('category')->latest()->limit(3)->get()),
            'packageCount' => TravelPackage::count(),
        ]);
    }
}
