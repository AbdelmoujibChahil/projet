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
/**
 * @OA\Get(
 *     path="/api/v1/admin/dashboard/stats",
 *     summary="Get dashboard KPIs",
 *     description="Returns key performance indicators for admin dashboard",
 *     tags={"Dashboard"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="KPIs retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="total_orders",
 *                 type="integer",
 *                 example=120
 *             ),
 *             @OA\Property(
 *                 property="revenue_today",
 *                 type="number",
 *                 example=2500.50
 *             ),
 *             @OA\Property(
 *                 property="active_orders",
 *                 type="integer",
 *                 example=15
 *             ),
 *             @OA\Property(
 *                 property="new_customers",
 *                 type="integer",
 *                 example=8
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
    public function getKpis()
    {
        return $this->service->getKpis();
    }
/**
 * @OA\Get(
 *     path="/api/v1/admin/dashboard/chart/revenue/{period}",
 *     summary="Get revenue trends",
 *     description="Returns revenue data for charts based on selected period",
 *     tags={"Dashboard"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="period",
 *         in="path",
 *         required=true,
 *         description="Time period (7days, 30days, 1year)",
 *         @OA\Schema(
 *             type="string",
 *             enum={"7days","30days","1year"}
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Revenue chart data",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="labels",
 *                 type="array",
 *                 @OA\Items(type="string"),
 *                 example={"Mon","Tue","Wed"}
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(type="number"),
 *                 example={120, 300, 250}
 *             )
 *         )
 *     )
 * )
 */
    public function getRevenueTrends(PeriodRequest $request)
    {
        return $this->service->getRevenueTrends($request->period);
    }
/**
 * @OA\Get(
 *     path="/api/v1/admin/dashboard/getOrderDistribution/{period}",
 *     summary="Get order distribution",
 *     description="Returns order status distribution for analytics dashboard",
 *     tags={"Dashboard"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="period",
 *         in="path",
 *         required=true,
 *         description="Time period (today, 7days, 30days)",
 *         @OA\Schema(
 *             type="string",
 *             enum={"today","7days","30days"}
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Order distribution data",
 *         @OA\JsonContent(
 *             @OA\Property(property="total_orders", type="integer", example=50),
 *             @OA\Property(property="completion_rate", type="number", example=75.5),
 *             @OA\Property(property="active_orders", type="integer", example=10),
 *             @OA\Property(
 *                 property="distribution",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="statut", type="string", example="pending"),
 *                     @OA\Property(property="count", type="integer", example=5),
 *                     @OA\Property(property="percentage", type="number", example=25)
 *                 )
 *             )
 *         )
 *     )
 * )
 */
    public function getOrderDistribution(PeriodRequest $request)
    {
        return $this->service->getOrderDistribution($request->period);
    }
}