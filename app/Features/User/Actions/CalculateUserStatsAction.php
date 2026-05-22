<?php

namespace App\Features\User\Actions;

class CalculateUserStatsAction
{
    public function execute($user)
    {
        $totalOrders = $user->commandes->count();
        $totalSpent = $user->commandes->sum('prix_total');

        $lastOrder = optional(
            $user->commandes->sortByDesc('created_at')->first()
        )->created_at;

        $tier = "bronze";
        if ($totalSpent > 3000) $tier = "gold";
        else if ($totalSpent > 1500) $tier = "silver";

        $status = ($lastOrder && $lastOrder->gt(now()->subDays(90)))
            ? "active"
            : "inactive";

        return [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,

            "totalOrders" => $totalOrders,
            "totalSpent" => $totalSpent,

            "tier" => $tier,
            "status" => $status,

            "lastOrder" => optional($lastOrder)->format('Y-m-d'),
        ];
    }
}