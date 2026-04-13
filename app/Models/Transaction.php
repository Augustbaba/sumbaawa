<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'transaction_id',
        'amount',
        'payment_method',
        'user_id',
        'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // admin qui a traité la transaction
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
