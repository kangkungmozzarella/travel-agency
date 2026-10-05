<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TravelPackageRequest extends FormRequest
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
                    'type' => ['required', 'string', 'max:255'],
                    'location' => ['required', 'string', 'max:255'],
                    'price' => ['required', 'integer', 'min:0', 'max:2147483647'],
                    'description' => ['required', 'string', 'max:50000'],
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'type' => ['required', 'string', 'max:255'],
                    'location' => ['required', 'string', 'max:255'],
                    'price' => ['required', 'integer', 'min:0', 'max:2147483647'],
                    'description' => ['required', 'string', 'max:50000'],
                ];

        }
    }
}
