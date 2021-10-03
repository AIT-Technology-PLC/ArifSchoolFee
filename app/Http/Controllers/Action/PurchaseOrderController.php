<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Purchase Order');
    }

    public function close(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('update', $purchaseOrder);

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->purchaseOrderDetails()->update([
                'quantity_left' => 0,
            ]);

            $purchaseOrder->close();
        });

        return back();
    }
}
