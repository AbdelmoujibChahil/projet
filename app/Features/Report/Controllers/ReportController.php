<?php

namespace App\Features\Report\Controllers;

use App\Models\Report;
use App\Features\Report\Services\ReportService;
use App\Features\Report\Requests\StoreReportRequest;
use App\Features\Report\Requests\ReportFilterRequest;
use App\Features\Report\Resources\ReportResource;
use App\Features\Report\Resources\ReportCollection;
use App\Http\Controllers\Controller;
class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function store(StoreReportRequest $request)
    {
        $report = $this->service->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Report submitted successfully',
            'report' => new ReportResource($report)
        ], 201);
    }

    public function index(ReportFilterRequest $request)
    {
        return new ReportCollection(
            $this->service->getAll(
                $request->validated()
            )
        );
    }

    public function markAsRead(Report $report)
    {
        return new ReportResource(
            $this->service->markAsRead($report)
        );
    }

    public function markAsResolved(Report $report)
    {
        return new ReportResource(
            $this->service->markAsResolved($report)
        );
    }

    public function destroy(Report $report)
    {
        $this->service->delete($report);

        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
    }

    public function kpis()
    {
        return response()->json(
            $this->service->getKPIs()
        );
    }

    public function dashboard()
    {
        return response()->json(
            $this->service->getDashboard()
        );
    }
}