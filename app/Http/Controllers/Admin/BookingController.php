<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', Rule::in(Booking::STATUSES)]]);
        $bookings = Booking::with('travel_package:id,location')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where(fn ($query) => $query->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Index', ['kind' => 'bookings', 'items' => $bookings, 'filters' => $filters]);
    }

    public function update(Request $request, Booking $booking)
    {
        $booking->update($request->validate(['status' => ['required', Rule::in(Booking::STATUSES)]]));

        return back()->with('message', 'Status booking diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return back()->with('message', 'Booking dihapus.');
    }
}
