<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:100']]);
        $users = User::select('id', 'name', 'email', 'is_admin', 'created_at')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where(fn ($query) => $query->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')))
            ->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Index', ['kind' => 'users', 'items' => $users, 'filters' => $filters]);
    }
}
