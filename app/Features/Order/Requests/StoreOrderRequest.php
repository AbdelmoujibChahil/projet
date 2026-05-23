<?php

namespace App\Features\Order\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'adresse_livraison_id' =>
                'required|exists:adresses_livraison,id',

            'paymentMethod' =>
                'required|string',

            'plats' =>
                'required|array|min:1',

            'plats.*.plat_id' =>
                'required|exists:plats,id',

            'plats.*.quantite' =>
                'required|integer|min:1',
        ];
    }
}