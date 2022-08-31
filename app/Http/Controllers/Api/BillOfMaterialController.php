<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BillOfMaterial;

class BillOfMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Bill Of Material Management');
    }

    public function index()
    {
        $billOfMaterials = BillOfMaterial::with(['product'])->active()->approved()->orderBy('name')->get();

        return $billOfMaterials->map(function ($billOfMaterial) {
            return [
                'id' => $billOfMaterial->id,
                'name' => $billOfMaterial->name,

                'product_id' => $billOfMaterial->product_id,
            ];
        });
    }
}
