<?php

namespace App\Features\Analytics\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /*  STATS  */
    public function getStats(string $period = 'week'): array
    {
        [$start, $end] = $this->getDateRange($period);
        [$prevStart, $prevEnd] = $this->getPreviousDateRange($period);

        $revenue = $this->revenue($start, $end);
        $prevRevenue = $this->revenue($prevStart, $prevEnd);

        $orders = $this->orders($start, $end);
        $prevOrders = $this->orders($prevStart, $prevEnd);

        $avg = $orders ? $revenue / $orders : 0;
        $prevAvg = $prevOrders ? $prevRevenue / $prevOrders : 0;

        $satisfaction = $this->satisfaction($start, $end);
        $prevSat = $this->satisfaction($prevStart, $prevEnd);

        return [
            'totalRevenue' => $this->metric($revenue, $prevRevenue),
            'totalOrders' => $this->metric($orders, $prevOrders),
            'avgOrderValue' => $this->metric($avg, $prevAvg),

            'customerSatisfaction' => [
                'value' => round($satisfaction, 1),
                'maxValue' => 5,
                'change' => round($satisfaction - $prevSat, 1),
                'trend' => ($satisfaction - $prevSat) >= 0 ? 'up' : 'down'
            ]
        ];
    }

    /*  REVENUE  */
    public function revenueTrends(string $period = 'week'): array
    {
        return [
            'current' => $this->revenueByPeriod($period, true),
            'previous' => $this->revenueByPeriod($period, false),
        ];
    }

    private function revenueByPeriod(string $period = 'week', bool $current = true)
    {
        [$start, $end] = $current ? $this->getDateRange($period) : $this->getPreviousDateRange($period);

        $groupBy = match ($period) {
            'today' => 'HOUR(created_at)',
            'week' => 'DATE(created_at)',
            'month' => 'DATE(created_at)',
            default => 'DATE(created_at)',
        };

        return DB::table('commandes')
            ->select(DB::raw("{$groupBy} as period"), DB::raw('SUM(prix_total) as revenue'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    /*  TOP CATEGORIES  */
    public function topCategories(string $period = 'week')
    {
        [$start, $end] = $this->getDateRange($period);

        return DB::table('commande_plat')
            ->join('plats', 'commande_plat.plat_id', '=', 'plats.id')
            ->join('categories', 'plats.category_id', '=', 'categories.id')
            ->join('commandes', 'commande_plat.commande_id', '=', 'commandes.id')
            ->select('categories.nom', DB::raw('SUM(commande_plat.quantite) as total'))
            ->whereBetween('commandes.created_at', [$start, $end])
            ->groupBy('categories.nom')
            ->orderByDesc('total')
            ->get();
    }

    /*  PAYMENT METHODS  */
    public function paymentMethods(string $period = 'month')
    {
        [$start, $end] = $this->getDateRange($period);

        $total = DB::table('commandes')->whereBetween('created_at', [$start, $end])->count();

        return DB::table('commandes')
            ->select('paymentMethod', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('paymentMethod')
            ->get()
            ->map(fn($item) => [
                'method' => $item->paymentMethod,
                'percentage' => $total ? round(($item->total / $total) * 100, 2) : 0
            ]);
    }

    /*  TOP PRODUCTS  */
    public function topProducts(int $limit = 5)
    {
        return DB::table('commande_plat')
            ->join('plats', 'commande_plat.plat_id', '=', 'plats.id')
            ->select('plats.nom', DB::raw('SUM(commande_plat.quantite) as total'))
            ->groupBy('plats.nom')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /*  PEAK HOURS  */
    public function peakHours()
    {
        return DB::table('commandes')
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as orders'))
            ->groupBy('hour')
            ->orderByDesc('orders')
            ->get();
    }

    /*  CUSTOMER METRICS  */
    public function customerMetrics(string $period = 'week')
    {
        [$start, $end] = $this->getDateRange($period);

        return DB::table('users')
            ->selectRaw("
                COUNT(*) as count,
                CASE
                    WHEN created_at BETWEEN ? AND ? THEN 'new'
                    ELSE 'returning'
                END as type
            ", [$start, $end])
            ->groupBy('type')
            ->get();
    }

    /*  HELPERS  */
    private function revenue($start, $end)
    {
        return DB::table('commandes')
            ->whereBetween('created_at', [$start, $end])
            ->sum('prix_total');
    }

    private function orders($start, $end)
    {
        return DB::table('commandes')
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }

    private function satisfaction($start, $end)
    {
        return DB::table('ratings')
            ->whereBetween('created_at', [$start, $end])
            ->avg('rating') ?? 0;
    }

    private function metric($current, $previous): array
    {
        $change = $previous == 0 ? 100 : (($current - $previous) / $previous) * 100;

        return [
            'value' => round($current, 2),
            'change' => round($change, 1),
            'trend' => $change >= 0 ? 'up' : 'down'
        ];
    }

    private function getDateRange($period)
    {
        return match ($period) {
            'today' => [Carbon::today(), Carbon::now()],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()],
            default => [Carbon::now()->startOfWeek(), Carbon::now()],
        };
    }

    private function getPreviousDateRange($period)
    {
        return match ($period) {
            'today' => [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()],
            'week' => [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()],
            'month' => [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()],
            default => [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()],
        };
    }
}