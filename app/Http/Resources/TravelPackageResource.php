<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TravelPackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'type' => $this->type,
            'slug' => $this->slug,
            'price' => (int) $this->price,
            'description' => $this->description,
            'image_url' => $this->galleries->first() ? Storage::url($this->galleries->first()->images) : asset('images/placeholder.svg'),
            'galleries' => $this->galleries->map(fn ($gallery) => [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'image_url' => Storage::url($gallery->images),
            ]),
        ];
    }
}
