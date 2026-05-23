<?php

namespace App\Features\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest
    extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'statut' => [
                'required',
                'in:Pending,On Delivery,Completed'
            ]
        ];
    }
}