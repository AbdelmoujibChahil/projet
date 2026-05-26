<?php

namespace App\Features\Driver\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            // USER
            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|string|min:6',

            'phone' => 'required|string|max:20|unique:users,phone',

            // DRIVER
            'vehicle_type' => 'required|string|max:255',

            'vehicle_plate' => 'nullable|string|max:255',

            'current_location' => 'nullable|string|max:255',

            'profile_image' => 'nullable|string',
        ];
    }
}