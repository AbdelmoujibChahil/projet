<?php

namespace App\Features\Product\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Plat;

use App\Features\Product\Services\ProductService;

use App\Features\Product\Requests\StoreProductRequest;
use App\Features\Product\Requests\UpdateProductRequest;

use App\Features\Product\Resources\ProductResource;

class ProductController extends Controller
{
    public function index(ProductService $service)
    {
        $products = $service->getAll();

        return ProductResource::collection($products);
    }
 //MODEL BINDING
    public function show(Plat $plat)
    {
        return new ProductResource(
            $plat->load('category')
                 ->loadAvg('ratings', 'rating')
                 ->loadCount('ratings')
        );
    }

    public function store(StoreProductRequest $request, ProductService $service) {
        $product = $service->create($request->validated());
        return response()->json(
            new ProductResource($product),
            201
        );
    }

    public function update(UpdateProductRequest $request,Plat $plat,ProductService $service) {
        $product = $service->update(
            $plat,
            $request->validated()
        );

        return response()->json(
            new ProductResource($product)
        );
    }

    public function destroy(Plat $plat,ProductService $service) {
        $service->delete($plat);

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}