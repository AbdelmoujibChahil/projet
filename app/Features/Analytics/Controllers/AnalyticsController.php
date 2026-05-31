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
/**
 * @OA\Get(
 *     path="/api/v1/analytics/stats",
 *     summary="Get general analytics stats",
 *     description="Returns global statistics for dashboard analytics",
 *     tags={"Analytics"},
 *
 *     @OA\Parameter(
 *         name="period",
 *         in="query",
 *         required=false,
 *         description="Time period (week, month, year)",
 *         @OA\Schema(type="string", example="week")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Stats retrieved successfully"
 *     )
 * )
 */
    public function stats(Request $request)
    {
        return response()->json(
            $this->service->getStats($request->period ?? 'week')
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/analytics/revenue-trends",
 *     summary="Get revenue trends",
 *     tags={"Analytics"},
 *
 *     @OA\Parameter(
 *         name="period",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", example="week")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Revenue trends data"
 *     )
 * )
 */
    public function revenueTrends(Request $request)
    {
        return response()->json(
            $this->service->revenueTrends($request->period ?? 'week')
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/analytics/top-categories",
 *     summary="Get top categories",
 *     tags={"Analytics"},
 *
 *     @OA\Parameter(
 *         name="period",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", example="week")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Top categories data"
 *     )
 * )
 */
    public function topCategories(Request $request)
    {
        return response()->json(
            $this->service->topCategories($request->period ?? 'week')
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/analytics/payment-methods",
 *     summary="Get payment methods distribution",
 *     tags={"Analytics"},
 *
 *     @OA\Parameter(
 *         name="period",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", example="month")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Payment methods stats"
 *     )
 * )
 */
    public function paymentMethods(Request $request)
    {
        return response()->json(
            $this->service->paymentMethods($request->period ?? 'month')
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/analytics/top-products",
 *     summary="Get top selling products",
 *     tags={"Analytics"},
 *
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Top products list"
 *     )
 * )
 */
    public function topProducts(Request $request)
    {
        return response()->json(
            $this->service->topProducts($request->limit ?? 5)
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/analytics/peak-hours",
 *     summary="Get peak ordering hours",
 *     tags={"Analytics"},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Peak hours data"
 *     )
 * )
 */
    public function peakHours()
    {
        return response()->json(
            $this->service->peakHours()
        );
    }
/**
 * @OA\Get(
 *     path="/api/v1/analytics/customer-metrics",
 *     summary="Get customer analytics metrics",
 *     tags={"Analytics"},
 *
 *     @OA\Parameter(
 *         name="period",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string", example="week")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Customer metrics data"
 *     )
 * )
 */
    public function customerMetrics(Request $request)
    {
        return response()->json(
            $this->service->customerMetrics($request->period ?? 'week')
        );
    }

}