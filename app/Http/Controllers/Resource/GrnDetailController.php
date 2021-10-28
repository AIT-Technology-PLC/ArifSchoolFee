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

        if ($grnDetail->grn->isAdded()) {
            abort(403);
        }

        if ($grnDetail->grn->isApproved() && !auth()->user()->can('Delete Approved GRN')) {
            abort(403);
        }

        $grnDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
