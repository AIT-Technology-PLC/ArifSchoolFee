<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;

class BranchController extends Controller
{
    public function getBranchesBySchool($schoolId)
    { 
        $branches = Warehouse::where('company_id', $schoolId)->withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        if ($branches->isEmpty()) {
            return response()->json(['error' => 'Branch data not found'], 500);
        }

        return response()->json($branches);
    }
}
