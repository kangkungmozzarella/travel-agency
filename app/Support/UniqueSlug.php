<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UniqueSlug
{
    public static function make(string $model, string $title, ?Model $except = null): string
    {
        $base = Str::slug($title) ?: 'item';
        $slug = $base;
        $suffix = 2;

        while ($model::where('slug', $slug)->when($except, fn ($query) => $query->whereKeyNot($except->getKey()))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
