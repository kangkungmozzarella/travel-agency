<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(BookingRequest $request)
    {
        Booking::create($request->validated());

        return redirect()->back()->with([
            'message' => 'Permintaan booking diterima. Tim kami akan menghubungi Anda untuk konfirmasi.',
        ]);
    }
}
