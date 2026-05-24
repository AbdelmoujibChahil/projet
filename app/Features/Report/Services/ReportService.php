<?php

namespace App\Features\Report\Services;

use App\Models\Report;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function create(array $data)
    {
        return Report::create($data);
    }

    public function getAll(array $filters)
    {
        $query = Report::query();

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        return $query
            ->latest()
            ->paginate(10);
    }

    public function markAsRead(Report $report)
    {
        if ($report->status === 'unread') {
            $report->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        return $report;
    }

    public function markAsResolved(Report $report)
    {
        $report->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'read_at' => $report->read_at ?? now(),
        ]);

        return $report;
    }

    public function delete(Report $report)
    {
        return $report->delete();
    }

    public function getKPIs()
    {
        $total = Report::count();
        $resolved = Report::resolved()->count();

        return [
            'total_reports' => $total,

            'unread' => Report::unread()->count(),
            'read' => Report::read()->count(),
            'resolved' => $resolved,

            'high_priority' => Report::where('priority', 'high')->count(),
            'medium_priority' => Report::where('priority', 'medium')->count(),
            'low_priority' => Report::where('priority', 'low')->count(),

            'avg_resolution_time' =>
                Report::whereNotNull('resolved_at')
                    ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_time'))
                    ->value('avg_time') ?? 0,

            'resolution_rate' =>
                $total > 0
                    ? round(($resolved / $total) * 100, 2)
                    : 0,

            'overdue_reports' =>
                Report::where('status', '!=', 'resolved')
                    ->where('created_at', '<', now()->subDay())
                    ->count(),
        ];
    }

    public function getDashboard()
    {
        return [
            'kpis' => $this->getKPIs(),

            'recent_reports' => Report::latest()
                ->limit(5)
                ->get(),

            'priority_distribution' => [
                'high' => Report::where('priority', 'high')->count(),
                'medium' => Report::where('priority', 'medium')->count(),
                'low' => Report::where('priority', 'low')->count(),
            ],

            'status_distribution' => [
                'unread' => Report::unread()->count(),
                'read' => Report::read()->count(),
                'resolved' => Report::resolved()->count(),
            ],
        ];
    }
}
