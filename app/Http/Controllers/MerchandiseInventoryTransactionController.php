<?php

namespace App\Http\Controllers;

use App\Models\Gdn;
use App\Models\Grn;
use App\Models\Purchase;
use App\Models\Sale;
use App\Services\AddPurchasedItemsToInventory;
use App\Services\StoreSaleableProducts;
use Illuminate\Support\Facades\DB;

class MerchandiseInventoryTransactionController extends Controller
{
    public function addToInventory($purchase)
    {
        $purchase = Purchase::find($purchase) ?? Grn::find($purchase);

        if ($purchase->getTable() == 'grns' && !$purchase->isGrnApproved()) {
            return redirect()->back()->with('message', 'This GRN is not approved');
        }

        DB::transaction(function () use ($purchase) {
            $purchase->changeStatusToAddedToInventory();
            AddPurchasedItemsToInventory::addToInventory($purchase);
        });

        return redirect()->back();
    }

    public function subtractFromInventory($sale)
    {
        $sale = Sale::find($sale) ?? Gdn::find($sale);

        if ($sale->getTable() == 'gdns' && !$sale->isGdnApproved()) {
            return redirect()->back()->with('message', 'This DO/GDN is not approved');
        }

        DB::transaction(function () use ($sale) {
            $sale->changeStatusToSubtractedFromInventory();
            $isSaleValid = StoreSaleableProducts::storeSoldProducts($sale);

            if (!$isSaleValid) {
                DB::rollback();
            }
        });

        return redirect()->back();
    }
}
