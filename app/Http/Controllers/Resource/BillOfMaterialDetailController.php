<?php

namespace App\Http\Controllers;

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

        abort_if(!auth()->user()->can('Delete BOM'), 403);

        $billOfMaterialDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}