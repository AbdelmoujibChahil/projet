<?php
namespace App\Features\Driver\Controllers;

use App\Features\Driver\Enums\DriverStatus;
use App\Features\Driver\Requests\AssignDriverRequest;
use App\Features\Driver\Requests\StoreDriverRequest;
use App\Features\Driver\Requests\UpdateDriverRequest;
use App\Features\Driver\Requests\UpdateDriverStatusRequest;
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

    public function store(StoreDriverRequest $request)
    {
        return response()->json(
            $this->service->create(
                $request->validated()
            )
        );
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->service->getAll($request->all())
        );
    }

    public function show(Driver $driver)
    {
        return response()->json(
            $this->service->getById($driver->id)
        );
    }

    public function update(UpdateDriverRequest $request,Driver $driver)
    {
        return response()->json(
            $this->service->update(
                $driver,
                $request->validated()
            )
        );
}
    public function destroy(Driver $driver)
    {
        $this->service->delete($driver);

        return response()->json([
            'message' => 'Driver deleted'
        ]);
    }


    public function updateStatus(Request $request,Driver $driver)
    {
        return response()->json(
            $this->service->updateStatus($driver,DriverStatus::from($request->statut))
        );
    }

    public function assignToOrder(AssignDriverRequest $request)
    {
        return response()->json($this->service->assignDriver($request->commande_id,$request->driver_id)
        );
    }

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

    public function dashboard()
    {
        return response()->json($this->service->dashboard());
    }

    public function myDeliveries()
    {
        return response()->json($this->service->getMyDeliveries(auth()->id()));
    }
}