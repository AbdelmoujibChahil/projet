<?php

namespace App\Features\Product\Services;

use App\Models\Plat;

class ProductService
{
    public function getAll()
    {
        return Plat::with('category')
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->get();
    }

    public function create(array $data)
    {
        return Plat::create($data);
    }

    public function update(Plat $plat, array $data)
    {
        $plat->update($data);

        return $plat;
    }

    public function delete(Plat $plat)
    {
        return $plat->delete();
    }
}