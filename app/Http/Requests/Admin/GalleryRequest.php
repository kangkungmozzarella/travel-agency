<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => ['required', 'string', 'max:255'],
                    'images' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'name' => ['required', 'string', 'max:255'],
                    'images' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
                ];

        }
    }
}
