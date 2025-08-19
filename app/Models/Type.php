<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $fillable = ['label', 'slug'];
    protected $table = 'types';
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}
