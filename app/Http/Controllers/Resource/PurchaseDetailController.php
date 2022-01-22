<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\PurchaseDetail;

class PurchaseDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Purchase Management');
    }

    public function destroy(PurchaseDetail $purchaseDetail)
    {
        $this->authorize('delete', $purchaseDetail->purchase);

        abort_if($purchaseDetail->purchase->isPurchased(), 403);

        abort_if($purchaseDetail->purchase->isApproved() && !auth()->user()->can('Delete Approved Purchase'), 403);

        $purchaseDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
