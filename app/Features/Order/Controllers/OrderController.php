<?php

namespace App\Features\Order\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Commande;

use App\Features\Order\Services\OrderService;

use App\Features\Order\Requests\StoreOrderRequest;

use App\Features\Order\Requests\UpdateOrderStatusRequest;

use App\Features\Order\Resources\OrderResource;
/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Orders management endpoints"
 * )
 */
class OrderController extends Controller
{/**
 * @OA\Post(
 *     path="/api/v1/orders",
 *     summary="Create a new order",
 *     description="Create a new order for the authenticated customer",
 *     tags={"Orders"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"adresse_livraison_id","payment_method","plats"},
 *
 *             @OA\Property(
 *                 property="adresse_livraison_id",
 *                 type="integer",
 *                 example=1
 *             ),
 *
 *             @OA\Property(
 *                 property="payment_method",
 *                 type="string",
 *                 example="cash"
 *             ),
 *
 *             @OA\Property(
 *                 property="plats",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(
 *                         property="plat_id",
 *                         type="integer",
 *                         example=2
 *                     ),
 *                     @OA\Property(
 *                         property="quantite",
 *                         type="integer",
 *                         example=3
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Order created successfully"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

    /* CREATE ORDER */

    public function store(StoreOrderRequest $request,OrderService $service) {

        $order = $service->create(
            $request->validated(),
            auth()->user()
        );

        return response()->json([
            'message' => 'Order created successfully',

            'order' => new OrderResource($order)

        ], 201);
    }
/**
 * @OA\Get(
 *     path="/api/v1/admin/orders",
 *     summary="Get all orders",
 *     description="Retrieve all orders (Admin only)",
 *     tags={"Admin Orders"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Orders retrieved successfully"
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     )
 * )
 */

    /* ADMIN - ALL ORDERS*/

    public function index(OrderService $service)
    {
        return OrderResource::collection(
            $service->getAll()
        );
    }

    /**
 * @OA\Get(
 *     path="/api/v1/orders/client",
 *     summary="Get authenticated client orders",
 *     description="Returns all orders belonging to the authenticated client",
 *     tags={"Orders"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="List of client orders"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */

    /* CLIENT ORDERS*/

    public function getClientOrders(OrderService $service) {

        return OrderResource::collection(
            $service->getClientOrders(
                auth()->id()
            )
        );
    }
/**
 * @OA\Patch(
 *     path="/api/v1/admin/orders/{commande}",
 *     summary="Update order status",
 *     description="Update the status of an order (Admin only)",
 *     tags={"Admin Orders"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="commande",
 *         in="path",
 *         required=true,
 *         description="Order ID",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"statut"},
 *             @OA\Property(
 *                 property="statut",
 *                 type="string",
 *                 enum={
 *                     "pending",
 *                     "preparing",
 *                     "delivering",
 *                     "completed",
 *                     "cancelled"
 *                 },
 *                 example="preparing"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Order status updated successfully"
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Order not found"
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     )
 * )
 */

    /*UPDATE STATUS*/

    public function updateStatus(UpdateOrderStatusRequest $request,Commande $commande,OrderService $service) {

        $order = $service->updateStatus(
            $commande,
            $request->validated()['statut']
        );

        return response()->json([
            'message' => 'Order status updated',

            'order' => new OrderResource($order)
        ]);
    }
}