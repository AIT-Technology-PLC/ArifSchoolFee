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

        $purchaseDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
