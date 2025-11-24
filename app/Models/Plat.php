<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plat extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'prix', 'image','review_count'];

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_plat')->withPivot('quantite');
    }
}
