<?php

namespace App\Features\Driver\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'status' => [
                'required',
                'in:active,on_delivery,offline'
            ]

        ];
    }
}