<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Auth;
use App;

class BackHelper
{
    public static function getFormattedPrice($price)
    {
        $price = floatval($price);
        return number_format($price, 0, '.', ' ');
    }
}
