<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\SaleDetail;

class SaleDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sale Management');
    }

    public function destroy(SaleDetail $saleDetail)
    {
        $this->authorize('delete', $saleDetail->sale);

        if ($saleDetail->sale->isApproved() || $saleDetail->sale->isCancelled()) {
            return back()->with('failedMessage', 'Invoices that are approved/cancelled can not be deleted.');
        }

        $saleDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
