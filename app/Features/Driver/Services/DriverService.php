<?php

namespace App\Features\Driver\Services;

use App\Features\Driver\Enums\DriverStatus;
use App\Models\Driver;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DriverService
{
    /* CREATE DRIVER*/
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'phone' => $data['phone'],
                'role' => 'driver',
            ]);

            return Driver::create([
                'user_id' => $user->id,
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_plate' => $data['vehicle_plate'] ?? null,
                'available' => true,
                'statut' => 'active',
            ]);
        });
    }

    /* LIST ALL DRIVERS*/
    public function getAll(array $filters = [])
    {
        return Driver::query()
            ->with('user')
            ->when($filters['status'] ?? null, fn($q, $status) =>
                $q->where('statut', $status)
            )
            ->when($filters['available'] ?? null, fn($q, $available) =>
                $q->where('available', $available)
            )
            ->latest()
            ->get();
    }

    /*SHOW DRIVER*/
    public function getById(int $id)
    {
        return Driver::with([
            'user',
            'commandes',
            'ratings'
        ])->findOrFail($id);
    }

    /*UPDATE DRIVER*/
    public function update(Driver $driver, array $data)
    {
        $driver->update([
            'vehicle_type' => $data['vehicle_type'] ?? $driver->vehicle_type,
            'vehicle_plate' => $data['vehicle_plate'] ?? $driver->vehicle_plate,
            'statut' => $data['statut'] ?? $driver->statut,
            'available' => $data['available'] ?? $driver->available,
        ]);

        return $driver->refresh();
    }

    /*DELETE DRIVER*/
    public function delete(Driver $driver)
    {
        return $driver->delete();
    }

    /* UPDATE STATUS*/
    public function updateStatus(Driver $driver,DriverStatus $status)
    {
        $driver->update(['statut' => $status->value]);

        return $driver->refresh();
    }

    /*ASSIGN DRIVER TO ORDER*/
    public function assignDriver(Commande $commande, Driver $driver)
    {
        return DB::transaction(function () use ($commande, $driver) {

            $commande->update([
                'driver_id' => $driver->id,
                'statut' => 'on_delivery',
            ]);

            $driver->increment('total_deliveries');
            $driver->update([
                'statut' => 'on_delivery',
                'available' => false
            ]);

            return $commande->load(['livreur.user']);
        });
    }

    /*AVAILABLE DRIVERS*/
    public function getAvailableDrivers(?int $excludeDriverId = null)
    {
        return Driver::query()
            ->where('available', true)
            ->where('statut', 'active')
            ->when($excludeDriverId, fn($q) =>
                $q->where('id', '!=', $excludeDriverId)
            )
            ->with('user')
            ->orderBy('rating', 'desc')
            ->get();
    }

    /*DRIVER DASHBOARD*/
    public function dashboard()
    {
        return [
            'total_drivers' => Driver::count(),
            'active' => Driver::where('statut', 'active')->count(),
            'on_delivery' => Driver::where('statut', 'on_delivery')->count(),
            'offline' => Driver::where('statut', 'offline')->count(),
            'avg_rating' => round(Driver::avg('rating'), 1),
            'total_deliveries' => Driver::sum('total_deliveries'),
        ];
    }

    /*DRIVER DELIVERIES (LOGGED USER)*/
    public function getMyDeliveries(int $userId)
    {
        $driver = Driver::where('user_id', $userId)->firstOrFail();

        return Commande::with(['user', 'plats', 'adresseLivraison'])
            ->where('driver_id', $driver->id)
            ->latest()
            ->get();
    }
}