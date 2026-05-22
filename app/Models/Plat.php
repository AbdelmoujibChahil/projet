<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;
use App\Models\Rating;

class Plat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image',
        'isAvailable',
        'isPopular',
        'isFeatured',
        'discount',
        'category_id'
    ];

    protected $casts = [
        'prix' => 'float',

        'discount' => 'float',

        'isAvailable' => 'boolean',

        'isPopular' => 'boolean',

        'isFeatured' => 'boolean',
    ];

    /* BOOTED*/

    protected static function booted()
    {
        static::created(function ($plat) {

            if ($plat->category_id) {
                $plat->category?->increment('total_products');
            }

        });

        static::deleted(function ($plat) {

            if ($plat->category_id) {
                $plat->category?->decrement('total_products');
            }

        });

        static::updated(function ($plat) {

            if ($plat->isDirty('category_id')) {

                $oldCategoryId = $plat->getOriginal('category_id');

                $newCategoryId = $plat->category_id;

                if ($oldCategoryId) {
                    Category::where('id', $oldCategoryId)
                        ->decrement('total_products');
                }

                if ($newCategoryId) {
                    Category::where('id', $newCategoryId)
                        ->increment('total_products');
                }
            }
        });
    }

    /* RELATIONS*/

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function commandes()
    {
        return $this->belongsToMany(
            Commande::class,
            'commande_plat'
        )->withPivot('quantite');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'plat_id');
    }
}