<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'symbol',
        'name',
        'exchange_rate',
        'is_default',
        'is_active',
    ];
    
}
