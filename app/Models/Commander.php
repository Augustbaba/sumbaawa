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
    ];
}
