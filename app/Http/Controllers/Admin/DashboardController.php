<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\TravelPackage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'pending' => Booking::where('status', 'pending')->count(),
                'packages' => TravelPackage::count(),
                'bookings' => Booking::count(),
                'blogs' => Blog::count(),
            ],
            'recentBookings' => Booking::with('travel_package:id,location')->latest()->limit(6)->get(),
            'upcomingBookings' => Booking::with('travel_package:id,location')->where('status', 'confirmed')->where('date', '>=', now()->toDateString())->orderBy('date')->limit(4)->get(),
        ]);
    }
}
