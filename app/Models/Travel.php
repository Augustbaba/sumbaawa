<?php

namespace App\Models;

use App\Helpers\FrontHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'canton_edition',
        'services_needed',
        'additional_info',
        'amount_xof',
        'amount_usd',
        'currency',
        'payment_method',
        'payment_status',
        'status',
        'paypal_order_id',
        'paypal_transaction_id',
        'paypal_details'
    ];

    protected $casts = [
        'paypal_details' => 'array',
        'services_needed' => 'array',
        'amount_xof' => 'decimal:2',
        'amount_usd' => 'decimal:2',
    ];


    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFormattedAmountAttribute(): string
    {
        return FrontHelper::format_currency($this->amount_xof);
    }

    public function getFormattedAmountUsdAttribute(): string
    {
        return '$' . number_format($this->amount_usd, 2);
    }
}
