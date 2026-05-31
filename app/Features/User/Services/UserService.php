<?php

namespace App\Features\User\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Features\User\Actions\CalculateUserStatsAction;

class UserService
{
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);
    }

    public function update(User $user, array $data, $image = null): User
    {
        if ($image) {
            $data['image'] = $image->store('users', 'public');
        }

        $user->update($data);

        return $user;
    }
    
    public function updatePassword(User $user, string $password): User
    {
        $user->update([
            'password' => Hash::make($password)
        ]);

        return $user;
    }

    public function updatePhone(User $user, string $phone): User
    {
        $user->update([
            'phone' => $phone
        ]);

        return $user;
    }

    public function getAllUsersWithStats()
    {
        $users = User::with(['commandes', 'ratings.plat'])->get();

        return $users->map(function ($user) {
            return (new CalculateUserStatsAction())->execute($user);
        });
    }
}