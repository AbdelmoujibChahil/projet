<?php

namespace App\Features\Driver\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'vehicle_type' => 'sometimes|string|max:255',

            'vehicle_plate' => 'nullable|string|max:255',

            'current_location' => 'nullable|string|max:255',

            'available' => 'sometimes|boolean',

            'profile_image' => 'nullable|string',
        ];
    }
}