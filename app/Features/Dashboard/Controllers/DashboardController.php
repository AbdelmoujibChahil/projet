<?php
namespace App\Features\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Dashboard\Services\DashboardService;
use App\Features\Dashboard\Requests\PeriodRequest;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $service
    ) {}
    
    public function getKpis()
    {
        return $this->service->getKpis();
    }

    public function getRevenueTrends(PeriodRequest $request)
    {
        return $this->service->getRevenueTrends($request->period);
    }

    public function getOrderDistribution(PeriodRequest $request)
    {
        return $this->service->getOrderDistribution($request->period);
    }
}