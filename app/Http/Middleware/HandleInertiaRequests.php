<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'inertia';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => ['user' => $request->user()?->only('id', 'name', 'email', 'is_admin')],
            'flash' => [
                'message' => fn () => $request->session()->get('message') ?? $request->session()->get('success'),
                'status' => fn () => $request->session()->get('status'),
            ],
            'contact' => [
                'phone' => config('travel.phone'),
                'whatsapp' => config('travel.whatsapp'),
            ],
        ];
    }
}
