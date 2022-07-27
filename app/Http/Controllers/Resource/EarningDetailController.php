<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\EarningDetail;

class EarningDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Earning Management');
    }

    public function destroy(EarningDetail $earningDetail)
    {
        $this->authorize('delete', $earningDetail->earning);

        if ($earningDetail->earning->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an earning that is approved.');
        }

        $earningDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
