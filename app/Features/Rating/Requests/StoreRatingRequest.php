<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'plat_id' => [
                'required',
                'exists:plats,id'
            ],

            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],

            'feedback' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }
}