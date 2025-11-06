<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    use HasFactory;
protected $hidden=['pivot'];
    protected $fillable = ['nom', 'description', 'prix', 'image'];

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_plat')->withPivot('quantite');
    }
}
