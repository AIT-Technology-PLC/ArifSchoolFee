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
        if ($gdnDetail->gdn->reservation()->exists()) {
            return back()
                ->with('failedMessage', "You cannot delete a DO that belongs to a reservation, instead cancel the reservation.");
        }

        if ($gdnDetail->gdn->isSubtracted()) {
            abort(403);
        }

        if ($gdnDetail->gdn->isApproved() && !auth()->user()->can('Delete Approved GDN')) {
            abort(403);
        }

        $gdnDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
