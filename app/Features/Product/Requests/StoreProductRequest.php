<?php

namespace App\Features\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',

            'prix' => 'required|numeric|min:0',

            'description' => 'nullable|string',

            'category_id' => 'required|exists:categories,id',

            'image' => 'nullable|string',
        ];
    }
}