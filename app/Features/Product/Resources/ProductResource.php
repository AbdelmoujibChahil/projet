<?php

namespace App\Features\Product\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'nom' => $this->nom,

            'prix' => $this->prix,

            'image' => $this->image,

            'description' => $this->description,

            'discount' => $this->discount ?? 0,

            'isAvailable' => $this->isAvailable ?? true,

            'isPopular' => $this->isPopular ?? false,

            'isFeatured' => $this->isFeatured ?? false,

            'category_id' => $this->category_id,

            'category' => $this->category,

            'rating' => round($this->ratings_avg_rating ?? 0, 1),

            'review_count' => $this->ratings_count ?? 0,
        ];
    }
}