<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function getJWTIdentifier()
{
    return $this->getKey(); // id de l’utilisateur
}

public function getJWTCustomClaims()
{
    return []; // infos supplémentaires (optionnel)
}

    
    protected $fillable = [
        'name',
        'email',
        'password','role',    'phone','adress'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function driver()
{
    return $this->hasOne(Driver::class, 'user_id'); 
}

public function commandes()
{
    return $this->hasMany(Commande::class);
}
public function ratings()
{
    return $this->hasMany(Rating::class, 'user_id');
}

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
