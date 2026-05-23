<?php

namespace App\Features\Order\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Commande;

use App\Features\Order\Services\OrderService;

use App\Features\Order\Requests\StoreOrderRequest;

use App\Features\Order\Requests\UpdateOrderStatusRequest;

use App\Features\Order\Resources\OrderResource;

class OrderController extends Controller
{
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

    /* ADMIN - ALL ORDERS*/

    public function index(OrderService $service)
    {
        return OrderResource::collection(
            $service->getAll()
        );
    }

    /* CLIENT ORDERS*/

    public function getClientOrders(OrderService $service) {

        return OrderResource::collection(
            $service->getClientOrders(
                auth()->id()
            )
        );
    }

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