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

        abort_if($transferDetail->transfer->isSubtracted(), 403);

        abort_if($transferDetail->transfer->isApproved() && ! authUser()->can('Delete Approved Transfer'), 403);

        $transferDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
