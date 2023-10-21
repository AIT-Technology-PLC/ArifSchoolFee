<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\GdnDetail;

class GdnDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Gdn Management');
    }

    public function destroy(GdnDetail $gdnDetail)
    {
        $this->authorize('delete', $gdnDetail->gdn);

        abort_if($gdnDetail->gdn->belongsToTransaction(), 403);

        abort_if($gdnDetail->gdn->isSubtracted() || $gdnDetail->gdn->isCancelled(), 403);

        abort_if($gdnDetail->gdn->isApproved() && !authUser()->can('Delete Approved GDN'), 403);

        $gdnDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
