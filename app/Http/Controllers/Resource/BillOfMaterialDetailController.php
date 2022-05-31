<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\BillOfMaterialDetail;

class BillOfMaterialDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Bill Of Material Management');
    }

    public function destroy(BillOfMaterialDetail $billOfMaterialDetail)
    {
        $this->authorize('delete', $billOfMaterialDetail->billOfMaterial);

        $billOfMaterialDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}