<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\AdjustmentDetail;

class AdjustmentDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');
    }

    public function destroy(AdjustmentDetail $adjustmentDetail)
    {
        $this->authorize('delete', $adjustmentDetail->adjustment);

        if ($adjustmentDetail->adjustment->isAdjusted()) {
            abort(403);
        }

        if ($adjustmentDetail->adjustment->isApproved() && !auth()->user()->can('Delete Approved Adjustment')) {
            abort(403);
        }

        $adjustmentDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
