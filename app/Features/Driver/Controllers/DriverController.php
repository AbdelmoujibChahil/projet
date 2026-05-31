<?php
namespace App\Features\Driver\Controllers;

use App\Features\Driver\Enums\DriverStatus;
use App\Features\Driver\Requests\AssignDriverRequest;
use App\Features\Driver\Requests\StoreDriverRequest;
use App\Features\Driver\Requests\UpdateDriverRequest;
use App\Features\Driver\Services\DriverService;
use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct(
        private DriverService $service
    ) {}
/**
 * @OA\Post(
 *     path="/api/v1/drivers",
 *     summary="Create a new driver",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","phone"},
 *             @OA\Property(property="name", type="string", example="Ahmed Driver"),
 *             @OA\Property(property="phone", type="string", example="0612345678"),
 *             @OA\Property(property="vehicle", type="string", example="Motorcycle")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Driver created successfully"
 *     )
 * )
 */
    public function store(StoreDriverRequest $request)
    {
        return response()->json(
            $this->service->create(
                $request->validated()
            )
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/drivers",
 *     summary="Get all drivers",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="List of drivers"
 *     )
 * )
 */
    public function index(Request $request)
    {
        return response()->json(
            $this->service->getAll($request->all())
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/drivers/{driver}",
 *     summary="Get driver details",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="driver",
 *         in="path",
 *         required=true,
 *         description="Driver ID",
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Driver details"
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Driver not found"
 *     )
 * )
 */
    public function show(Driver $driver)
    {
        return response()->json(
            $this->service->getById($driver->id)
        );
    }
/**
 * @OA\Put(
 *     path="/api/v1/drivers/{driver}",
 *     summary="Update driver",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="driver",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Ahmed Updated"),
 *             @OA\Property(property="phone", type="string", example="0611111111"),
 *             @OA\Property(property="vehicle", type="string", example="Car")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Driver updated successfully"
 *     )
 * )
 */
    public function update(UpdateDriverRequest $request,Driver $driver)
    {
        return response()->json(
            $this->service->update(
                $driver,
                $request->validated()
            )
        );
}
/**
 * @OA\Delete(
 *     path="/api/v1/drivers/{driver}",
 *     summary="Delete driver",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="driver",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Driver deleted successfully"
 *     )
 * )
 */
    public function destroy(Driver $driver)
    {
        $this->service->delete($driver);

        return response()->json([
            'message' => 'Driver deleted'
        ]);
    }

/**
 * @OA\Patch(
 *     path="/api/v1/drivers/{driver}/status",
 *     summary="Update driver status",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="driver",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"statut"},
 *             @OA\Property(
 *                 property="statut",
 *                 type="string",
 *                 example="available"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Driver status updated"
 *     )
 * )
 */
    public function updateStatus(Request $request,Driver $driver)
    {
        return response()->json(
            $this->service->updateStatus($driver,DriverStatus::from($request->statut))
        );
    }
/**
 * @OA\Post(
 *     path="/api/v1/drivers/assign",
 *     summary="Assign driver to order",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"driver_id","commande_id"},
 *             @OA\Property(
 *                 property="driver_id",
 *                 type="integer",
 *                 example=2
 *             ),
 *             @OA\Property(
 *                 property="commande_id",
 *                 type="integer",
 *                 example=15
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Driver assigned successfully"
 *     )
 * )
 */
    public function assignToOrder(AssignDriverRequest $request)
    {
        return response()->json($this->service->assignDriver($request->commande_id,$request->driver_id)
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/drivers/available",
 *     summary="Get available drivers",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="commande_id",
 *         in="query",
 *         required=false,
 *         description="Current order ID",
 *         @OA\Schema(type="integer")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Available drivers list"
 *     )
 * )
 */
    public function getAvailableDrivers(Request $request)
    {
        return response()->json(
            $this->service->getAvailableDrivers(
                $request->commande_id
                    ? Commande::find($request->commande_id)?->driver_id
                    : null
            )
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/drivers/dashboard",
 *     summary="Driver dashboard statistics",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Dashboard statistics"
 *     )
 * )
 */
    public function dashboard()
    {
        return response()->json($this->service->dashboard());
    }
/**
 * @OA\Get(
 *     path="/api/v1/drivers/me/deliveries",
 *     summary="Get my deliveries",
 *     description="Returns deliveries assigned to the authenticated driver",
 *     tags={"Drivers"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Driver deliveries"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
    public function myDeliveries()
    {
        return response()->json($this->service->getMyDeliveries(auth()->id()));
    }
}