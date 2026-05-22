<?php

namespace App\Features\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'email' => $this->email,

            'phone' => $this->phone,

            'role' => $this->role,

            'address' => $this->address,

            'created_at' => optional($this->created_at)
                ->format('Y-m-d H:i'),

        ];
    }
}