<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'number_phone' => ['required', 'string', 'regex:/^\+?[0-9()\s-]{8,20}$/'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'travel_package_id' => ['required', 'integer', 'exists:travel_packages,id'],
        ];
    }
}
