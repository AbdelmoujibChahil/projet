<?php

namespace App\Features\Report\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'search' => 'nullable|string|max:255',
        ];
    }
}