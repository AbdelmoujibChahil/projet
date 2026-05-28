<?php

namespace App\Features\Analytics\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Analytics\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $service
    ) {}

    public function stats(Request $request)
    {
        return response()->json(
            $this->service->getStats($request->period ?? 'week')
        );
    }

    public function revenueTrends(Request $request)
    {
        return response()->json(
            $this->service->revenueTrends($request->period ?? 'week')
        );
    }

    public function topCategories(Request $request)
    {
        return response()->json(
            $this->service->topCategories($request->period ?? 'week')
        );
    }

    public function paymentMethods(Request $request)
    {
        return response()->json(
            $this->service->paymentMethods($request->period ?? 'month')
        );
    }

    public function topProducts(Request $request)
    {
        return response()->json(
            $this->service->topProducts($request->limit ?? 5)
        );
    }

    public function peakHours()
    {
        return response()->json(
            $this->service->peakHours()
        );
    }

    public function customerMetrics(Request $request)
    {
        return response()->json(
            $this->service->customerMetrics($request->period ?? 'week')
        );
    }

}