<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;

class VehicleController extends Controller
{
    public function getVehiclesByRoute($routeId)
    {    
        $route = Route::find($routeId);

        if ($route) {
            $vehicles = $route->vehicles;
            return response()->json($vehicles);
        }

        return response()->json(['error' => 'Vehicle data not found'], 404);
    }
}
