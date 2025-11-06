<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

protected $hidden=['pivot'];
 
    public function plats()
{   
    return $this->belongsToMany(Plat::class, 'commande_plat')->withPivot('quantite');
}   

public function users()
{
    return $this->belongsTo(User::class);
}
public function calculertotale(){
        $prixTotale = 0;
    foreach($this->plats as $plat){ // $this refers to commande actuelle + plats refers to plats() function=>liees les tables =>faire requetes sql auto 
       $prixTotale= $plat->prix * $plat->Pivot->quantite ;
    }  
    return $prixTotale; 
    }


//Un utilisateur (User) peut passer plusieurs commandes.
//Une commande (Commande) appartient à un seul utilisateur.
public function user()
{
    return $this->belongsTo(User::class);
}

}
