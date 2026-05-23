<?php

namespace App\Features\Order\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [

            'id' => $this->id,

            'status' => $this->statut,

            'total' => $this->prix_total,

            'paymentMethod' =>$this->paymentMethod,

            'date' =>$this->date_commande,

            'user' => $this->whenLoaded('user'),

            'products' => $this->whenLoaded('plats'),

            'address' =>$this->whenLoaded('adresseLivraison'),
        ];
    }
}