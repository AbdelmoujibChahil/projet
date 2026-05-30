<?php
namespace App\Features\Dashboard\Services;

use App\Models\Commande;
use App\Models\User;
use Carbon\Carbon;

class DashboardService
{
    public function getKpis()
    {
        $today = Carbon::now()->startOfDay();
        $yesterday = Carbon::now()->subDay()->startOfDay();

        return response()->json([
            'total_commandes' => [
                'value' => $this->count($today),
                'trend' => $this->trend(
                    $this->count($today),
                    $this->count($yesterday, $today)
                ),
            ],
            'revenue_today' => [
                'value' => $this->revenue($today),
            ],
            'active_commandes' => [
                'value' => $this->active(),
            ],
            'new_customers' => [
                'value' => $this->newCustomers($today),
            ],
        ]);
    }

    public function getRevenueTrends($period)
    {
        [$start, $format] = $this->resolvePeriod($period);

        $data = Commande::selectRaw(
            'DATE_FORMAT(created_at, ?) as label, SUM(prix_total) as value',
            [$format]
        )
            ->where('created_at', '>=', $start)
            ->where('statut', 'completed')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        return response()->json([
            'labels' => $data->pluck('label'),
            'data' => $data->pluck('value'),
        ]);
    }

    public function getOrderDistribution($period)
    {
        $start = $this->resolveStart($period);

        $orders = Commande::where('created_at', '>=', $start)->get();

        $total = $orders->count();

        if ($total === 0) {
            return response()->json([
                'total_orders' => 0,
                'completion_rate' => 0,
                'active_orders' => 0,
                'distribution' => [],
            ]);
        }

        $grouped = $orders->groupBy('statut');

        $distribution = [];

        foreach (Commande::STATUTS as $status) {
            $count = $grouped[$status]->count() ?? 0;

            $distribution[] = [
                'statut' => $status,
                'count' => $count,
                'percentage' => round(($count / $total) * 100, 1),
            ];
        }

        return response()->json([
            'total_orders' => $total,
            'completion_rate' => $this->completionRate($grouped, $total),
            'active_orders' => $this->activeCount($grouped),
            'distribution' => $distribution,
        ]);
    }

    //  PRIVATE LOGIC 

    private function count($from, $to = null)
    {
        return Commande::where('created_at', '>=', $from)
            ->when($to, fn($q) => $q->where('created_at', '<', $to))
            ->count();
    }

    private function revenue($from)
    {
        return Commande::where('created_at', '>=', $from)
            ->where('statut', 'completed')
            ->sum('prix_total');
    }

    private function active()
    {
        return Commande::whereIn('statut', [
            'pending', 'preparing', 'delivering'
        ])->count();
    }

    private function newCustomers($from)
    {
        return User::where('created_at', '>=', $from)
            ->where('role', 'client')
            ->count();
    }

    private function trend($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? '+100%' : '0%';

        $p = (($current - $previous) / $previous) * 100;

        return ($p >= 0 ? '+' : '') . number_format($p, 1) . '%';
    }

    private function resolvePeriod($period)
    {
        return match ($period) {
            '7days' => [Carbon::now()->subDays(6), '%a'],
            '30days' => [Carbon::now()->subDays(29), '%d'],
            default => [Carbon::now()->subYear(), '%b'],
        };
    }

    private function resolveStart($period)
    {
        return match ($period) {
            'today' => Carbon::now()->startOfDay(),
            '7days' => Carbon::now()->subDays(6)->startOfDay(),
            '30days' => Carbon::now()->subDays(29)->startOfDay(),
            default => Carbon::now()->startOfDay(),
        };
    }

    private function completionRate($grouped, $total)
    {
        return round(
            (($grouped['completed']->count() ?? 0) / $total) * 100,
            1
        );
    }

    private function activeCount($grouped)
    {
        return collect(['pending', 'preparing', 'delivering'])
            ->sum(fn($s) => $grouped[$s]->count() ?? 0);
    }
}