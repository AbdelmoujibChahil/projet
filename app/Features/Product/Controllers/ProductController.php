<?php

namespace App\Features\Product\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Plat;

use App\Features\Product\Services\ProductService;

use App\Features\Product\Requests\StoreProductRequest;
use App\Features\Product\Requests\UpdateProductRequest;

use App\Features\Product\Resources\ProductResource;
/**
 * @OA\Tag(
 *     name="Products",
 *     description="Product management endpoints"
 * )
 */
class ProductController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/v1/products",
 *     tags={"Products"},
 *     summary="Get all products",
 *     description="Retrieve a list of all available products.",
 *     @OA\Response(
 *         response=200,
 *         description="Products retrieved successfully"
 *     )
 * )
 */
    public function index(ProductService $service)
    {
        $products = $service->getAll();

        return ProductResource::collection($products);
    }

    /**
 * @OA\Get(
 *     path="/api/v1/products/{plat}",
 *     tags={"Products"},
 *     summary="Get product details",
 *     description="Retrieve a single product by its ID including category and ratings statistics.",
 *     @OA\Parameter(
 *         name="plat",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product retrieved successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found"
 *     )
 * )
 */
 //MODEL BINDING
    public function show(Plat $plat)
    {
        return new ProductResource(
            $plat->load('category')
                 ->loadAvg('ratings', 'rating')
                 ->loadCount('ratings')
        );
    }
/**
 * @OA\Post(
 *     path="/api/v1/admin/products",
 *     tags={"Products"},
 *     summary="Create a new product",
 *     description="Create a new product. Admin access only.",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nom","prix","category_id"},
 *             @OA\Property(property="nom", type="string", example="Pizza Margherita"),
 *             @OA\Property(property="description", type="string", example="Classic Italian pizza"),
 *             @OA\Property(property="prix", type="number", format="float", example=79.99),
 *             @OA\Property(property="category_id", type="integer", example=1),
 *             @OA\Property(property="image", type="string", example="pizza.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product created successfully"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function store(StoreProductRequest $request, ProductService $service) {
        $product = $service->create($request->validated());
        return response()->json(
            new ProductResource($product),
            201
        );
    }
/**
 * @OA\Put(
 *     path="/api/v1/admin/products/{plat}",
 *     tags={"Products"},
 *     summary="Update a product",
 *     description="Update an existing product. Admin access only.",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="plat",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="nom", type="string", example="Pizza Royale"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="prix", type="number", format="float", example=89.99),
 *             @OA\Property(property="category_id", type="integer", example=2),
 *             @OA\Property(property="image", type="string"),
 *             @OA\Property(property="discount", type="string"),
 *             @OA\Property(property="isAvailable", type="string"),       
 *             @OA\Property(property="isFeatured", type="string")  ,     
 *             @OA\Property(property="isPopular", type="string"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found"
 *     )
 * )
 */
    public function update(UpdateProductRequest $request,Plat $plat,ProductService $service) {
        $product = $service->update(
            $plat,
            $request->validated()
        );

        return response()->json(
            new ProductResource($product)
        );
    }
/**
 * @OA\Delete(
 *     path="/api/v1/admin/products/{plat}",
 *     tags={"Products"},
 *     summary="Delete a product",
 *     description="Delete a product by ID. Admin access only.",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="plat",
 *         in="path",
 *         required=true,
 *         description="Product ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Product deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Product not found"
 *     )
 * )
 */
    public function destroy(Plat $plat,ProductService $service) {
        $service->delete($plat);

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}