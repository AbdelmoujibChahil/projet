<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
       const STATUTS = [
        'pending',
        'preparing',
        'delivering',
        'completed',
        'canceled'
    ];
   protected $fillable = [
    'user_id',
        'adresse_livraison_id',
        'prix_total',
         'paymentMethod', 
        'statut',
        'date_commande',
    ];

 
    public function plats()
{   
    return $this->belongsToMany(Plat::class, 'commande_plat')->withPivot('quantite');
}   

//Un utilisateur (User) peut passer plusieurs commandes.
//Une commande (Commande) appartient à un seul utilisateur.
public function user()
{
    return $this->belongsTo(User::class,'user_id');
}

public function AdresseLivraison()
{
return $this->belongsTo(AdresseLivraison::class);
}

public function payment()
{
    return $this->hasOne(Payment::class);
}





}
