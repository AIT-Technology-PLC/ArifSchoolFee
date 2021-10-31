<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderDetail;

class PurchaseOrderDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Purchase Order');
    }

    public function destroy(PurchaseOrderDetail $purchaseOrderDetail)
    {
        $this->authorize('delete', $purchaseOrderDetail->purchaseOrder);

        abort_if($purchaseOrderDetail->purchaseOrder->isClosed(), 403);

        $purchaseOrderDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
