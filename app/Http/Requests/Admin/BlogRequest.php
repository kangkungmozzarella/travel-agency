<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
                    'title' => ['required', 'string', 'max:255'],
                    'excerpt' => ['required', 'string', 'max:2000'],
                    'image' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
                    'description' => ['required', 'string', 'max:50000'],
                    'category_id' => ['required', 'integer', 'exists:categories,id'],
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'title' => ['required', 'string', 'max:255'],
                    'excerpt' => ['required', 'string', 'max:2000'],
                    'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:5120'],
                    'description' => ['required', 'string', 'max:50000'],
                    'category_id' => ['required', 'integer', 'exists:categories,id'],
                ];

        }
    }
}
