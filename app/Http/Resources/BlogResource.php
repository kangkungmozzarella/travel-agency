<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'image_url' => Storage::url($this->image),
            'category_id' => $this->category_id,
            'category' => $this->category?->only('id', 'name', 'slug'),
            'reads' => (int) $this->reads,
            'created_at' => $this->created_at?->toDateString(),
        ];
    }
}
