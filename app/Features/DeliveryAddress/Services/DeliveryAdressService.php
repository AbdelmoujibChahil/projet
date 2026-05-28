<?php

namespace App\Features\DeliveryAddress\Services;
use App\Models\AdresseLivraison;

class DeliveryAdressService
{
   
  public function create($data){
    $user = auth()->user();

    $address = AdresseLivraison::create([
        'user_id' => $user->id,
        'full_name' => $data['full_name'],
        'phone' => $data['phone'],
        'street_address' => $data['street_address'],
        'delivery_instructions' => $data['delivery_instructions'] ?? null,
    ]);

    return $address;
  }

}