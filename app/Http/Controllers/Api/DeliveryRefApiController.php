<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use App\Models\Type;

class DeliveryRefApiController extends Controller
{
    /**
     * GET /api/delivery/refs
     * Miroir de FrontHelper::allTypes() + FrontHelper::allPays()
     * Utilisé par le checkout Flutter pour peupler les dropdowns.
     */
    public function index()
    {
        return response()->json([
            'types' => Type::all(['id', 'label']),
            'pays'  => Pays::orderBy('name', 'asc')->get(['id', 'name']),
        ]);
    }
}
