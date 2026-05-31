<?php

namespace App\Features\Report\Controllers;

use App\Models\Report;
use App\Features\Report\Services\ReportService;
use App\Features\Report\Requests\StoreReportRequest;
use App\Features\Report\Requests\ReportFilterRequest;
use App\Features\Report\Resources\ReportResource;
use App\Features\Report\Resources\ReportCollection;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Reports",
 *     description="reports management endpoints"
 * )
 */
class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    /**
     * @OA\Post(
     *     path="/api/v1/reports",
     *     tags={"Reports"},
     *     summary="Create a new report",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Report created successfully"
     *     )
     * )
     */
    public function store(StoreReportRequest $request)
    {
        $report = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Report submitted successfully',
            'report' => new ReportResource($report)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reports",
     *     tags={"Reports"},
     *     summary="Get all reports",
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get all reports"
     *     )
     * )
     */
    public function index(ReportFilterRequest $request)
    {
        return new ReportCollection(
            $this->service->getAll($request->validated())
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/reports/{report}/read",
     *     tags={"Reports"},
     *     summary="Mark report as read",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="report",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mark report as read"
     *     )
     * )
     */
    public function markAsRead(Report $report)
    {
        return new ReportResource(
            $this->service->markAsRead($report)
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/reports/{report}/resolve",
     *     tags={"Reports"},
     *     summary="Mark report as resolved",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="report",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Report resolved"
     *     )
     * )
     */
    public function markAsResolved(Report $report)
    {
        return new ReportResource(
            $this->service->markAsResolved($report)
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reports/{report}",
     *     tags={"Reports"},
     *     summary="Delete a report",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="report",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Report deleted successfully"
     *     )
     * )
     */
    public function destroy(Report $report)
    {
        $this->service->delete($report);

        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reports/kpis",
     *     tags={"Reports"},
     *     summary="KPIs des reports",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="KPIs returned"
     *     )
     * )
     */
    public function kpis()
    {
        return response()->json(
            $this->service->getKPIs()
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reports/dashboard",
     *     tags={"Reports"},
     *     summary="Dashboard reports",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard data"
     *     )
     * )
     */
    public function dashboard()
    {
        return response()->json(
            $this->service->getDashboard()
        );
    }
}