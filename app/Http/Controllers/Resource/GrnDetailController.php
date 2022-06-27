<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\GrnDetail;

class GrnDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Grn Management');
    }

    public function destroy(GrnDetail $grnDetail)
    {
        $this->authorize('delete', $grnDetail->grn);

        abort_if($grnDetail->grn->isAdded(), 403);

        abort_if($grnDetail->grn->isApproved() && !authUser()->can('Delete Approved GRN'), 403);

        $grnDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
