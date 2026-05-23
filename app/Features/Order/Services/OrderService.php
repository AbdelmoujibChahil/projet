<?php

namespace App\Features\Order\Services;

use App\Models\Commande;

use Illuminate\Support\Facades\DB;

use App\Features\Order\Enums\OrderStatus;
use App\Models\Plat;

class OrderService
{
    public function create(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {

            $productIds = collect($data['plats'])
                ->pluck('plat_id');

            $products = Plat::whereIn(
                'id',
                $productIds
            )->get()->keyBy('id');

            $total = 0;

            foreach ($data['plats'] as $item) {

                $product = $products[$item['plat_id']];

                $total +=
                    $product->prix * $item['quantite'];
            }

            $deliveryFee = 10;

            $commande = Commande::create([
                'user_id' => $user->id,

                'adresse_livraison_id' =>
                    $data['adresse_livraison_id'],

                'prix_total' =>
                    $total + $deliveryFee,

                'paymentMethod' =>
                    $data['paymentMethod'],

                'statut' =>
                    OrderStatus::Pending->value,

                'date_commande' => now()
            ]);

            $pivotData = [];

            foreach ($data['plats'] as $item) {

                $pivotData[$item['plat_id']] = [
                    'quantite' => $item['quantite']
                ];
            }

            $commande->plats()->attach($pivotData);

            return $commande->load([
                'plats',
                'adresseLivraison'
            ]);
        });
    }
    public function getAll()
{
    return Commande::with([
        'plats',
        'user',
        'adresseLivraison'
    ])->latest()->get();
}

        public function getClientOrders($userId)
        {
            return Commande::with([
                'plats',
                'adresseLivraison'
            ])
            ->where('user_id', $userId)
            ->latest()
            ->get();
        }

        public function updateStatus(Commande $commande,string $status) {

            $commande->update([
                'statut' => $status
            ]);

            return $commande;
        }
}