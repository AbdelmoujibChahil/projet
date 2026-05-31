<?php
namespace App\Features\DeliveryAddress\Controllers;

use App\Features\DeliveryAddress\Requests\StoreDeliveryAdressRequest;
use App\Features\DeliveryAddress\Services\DeliveryAdressService;
use App\Http\Controllers\Controller;

class DeliveryAdressController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/v1/addresses",
 *     summary="Create a delivery address",
 *     description="Create a new delivery address for the authenticated user",
 *     tags={"Addresses"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"full_name","phone","street_address"},
 *
 *             @OA\Property(
 *                 property="full_name",
 *                 type="string",
 *                 example="John Doe"
 *             ),
 *
 *             @OA\Property(
 *                 property="phone",
 *                 type="string",
 *                 example="0612345678"
 *             ),
 *
 *             @OA\Property(
 *                 property="street_address",
 *                 type="string",
 *                 example="123 Main Street, Agadir"
 *             ),
 *
 *             @OA\Property(
 *                 property="delivery_instructions",
 *                 type="string",
 *                 example="Leave at the door",
 *                 nullable=true
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Address created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="full_name", type="string", example="John Doe"),
 *                 @OA\Property(property="phone", type="string", example="0612345678"),
 *                 @OA\Property(property="street_address", type="string", example="123 Main Street"),
 *                 @OA\Property(property="delivery_instructions", type="string", example="Leave at the door")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function store(StoreDeliveryAdressRequest $request,DeliveryAdressService $service){

    return response()->json([
        'success' => true,
        'data' => $service->create($request->validated())
    ], 201);
    }
    
}