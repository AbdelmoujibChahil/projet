<?php

namespace App\Features\DeliveryAddress\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryAdressRequest extends FormRequest
{
    /**
     * Autoriser la requête
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation
     */
    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ ]{3,}$/'
            ],

            'phone' => [
                'required',
                'regex:/^[0-9]{6,15}$/'
            ],

            'street_address' => [
                'required',
                'string',
                'min:6',
                'max:255',
            ],

            'delivery_instructions' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'full_name.regex' => 'Full name must contain only letters and be at least 3 characters.',

            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must contain only digits (6 to 15 characters).',

            'street_address.required' => 'Street address is required.',
            'street_address.min' => 'Street address is too short.',
        ];
    }
}