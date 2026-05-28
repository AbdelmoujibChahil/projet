<?php
namespace App\Features\DeliveryAddress\Controllers;

use App\Features\DeliveryAddress\Requests\StoreDeliveryAdressRequest;
use App\Features\DeliveryAddress\Services\DeliveryAdressService;
use App\Http\Controllers\Controller;

class DeliveryAdressController extends Controller
{
    public function store(StoreDeliveryAdressRequest $request,DeliveryAdressService $service){

    return response()->json([
        'success' => true,
        'data' => $service->create($request->validated())
    ], 201);
    }
    
}