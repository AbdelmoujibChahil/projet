<?php

namespace App\Features\Report\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => ReportResource::collection($this->collection),
        ];
    }
}