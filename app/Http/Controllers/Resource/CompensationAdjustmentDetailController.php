<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\CompensationAdjustmentDetail;

class CompensationAdjustmentDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Compensation Adjustment');
    }

    public function destroy(CompensationAdjustmentDetail $compensationAdjustmentDetail)
    {
        $this->authorize('delete', $compensationAdjustmentDetail->compensationAdjustment);

        if ($compensationAdjustmentDetail->compensationAdjustment->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an adjustment that is approved.');
        }

        $compensationAdjustmentDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
