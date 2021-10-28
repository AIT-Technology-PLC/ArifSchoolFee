<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\ReturnDetail;

class ReturnDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');
    }

    public function destroy(ReturnDetail $returnDetail)
    {
        $this->authorize('delete', $returnDetail->returnn);

        if ($returnDetail->returnn->isAdded()) {
            abort(403);
        }

        if ($returnDetail->returnn->isApproved() && !auth()->user()->can('Delete Approved Return')) {
            abort(403);
        }

        $returnDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
