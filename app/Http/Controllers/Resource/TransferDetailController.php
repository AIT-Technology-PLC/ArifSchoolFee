<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\TransferDetail;

class TransferDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Transfer Management');
    }

    public function destroy(TransferDetail $transferDetail)
    {
        $this->authorize('delete', $transferDetail->transfer);

        if ($transferDetail->transfer->isSubtracted()) {
            abort(403);
        }

        if ($transferDetail->transfer->isApproved() && !auth()->user()->can('Delete Approved Transfer')) {
            abort(403);
        }

        $transferDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
