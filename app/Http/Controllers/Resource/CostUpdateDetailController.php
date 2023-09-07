<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\CostUpdateDetail;

class CostUpdateDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Cost Update Management');
    }

    public function destroy(CostUpdateDetail $costUpdateDetail)
    {
        $this->authorize('delete', $costUpdateDetail->costUpdate);

        abort_if($costUpdateDetail->costUpdate->isRejected(), 403);

        abort_if($costUpdateDetail->costUpdate->isApproved(), 403);

        $costUpdateDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
