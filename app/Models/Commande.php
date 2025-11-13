<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
   protected $fillable = [
    'user_id',
        'adresse_livraison_id',
        'prix_total',
         'paymentMethod', 
        'statut',
        'date_commande',
    ];

protected $hidden=['pivot'];
 
    public function plats()
{   
    return $this->belongsToMany(Plat::class, 'commande_plat')->withPivot('quantite');
}   

//Un utilisateur (User) peut passer plusieurs commandes.
//Une commande (Commande) appartient à un seul utilisateur.
public function users()
{
    return $this->belongsTo(User::class,'user_id');
}

public function AdresseLivraison()
{
return $this->belongsTo(AdresseLivraison::class);
}





}
