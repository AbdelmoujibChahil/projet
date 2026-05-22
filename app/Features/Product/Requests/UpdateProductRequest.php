<?php

namespace App\Features\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'sometimes|string|max:255',

            'prix' => 'sometimes|numeric|min:0',

            'description' => 'nullable|string',

            'category_id' => 'sometimes|exists:categories,id',

            'image' => 'nullable|string',

            'discount' => 'nullable|numeric|min:0|max:100',

            'isAvailable' => 'nullable|boolean',

            'isPopular' => 'nullable|boolean',

            'isFeatured' => 'nullable|boolean',
        ];
    }
}