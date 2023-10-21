<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\SaleDetail;

class SaleDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sale Management');
    }

    public function destroy(SaleDetail $saleDetail)
    {
        $this->authorize('delete', $saleDetail->sale);

        abort_if($saleDetail->sale->belongsToTransaction(), 403);

        abort_if($saleDetail->sale->isApproved() || $saleDetail->sale->isCancelled(), 403);

        $saleDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
