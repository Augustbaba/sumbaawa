<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commander extends Model
{
    use HasFactory;
    protected $fillable = [
        'produit_id',
        'description_produit',
        'quantity',
        // 'delivery_info',
        'commande_id',
        'unit_price',
        'total_price'
    ];

    // Relation avec la commande
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    // Relation avec le produit
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }
}
