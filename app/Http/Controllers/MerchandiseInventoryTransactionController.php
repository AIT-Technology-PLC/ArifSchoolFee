<?php

namespace App\Http\Controllers;

use App\Models\Gdn;
use App\Models\Purchase;
use App\Services\AddPurchasedItemsToInventory;
use App\Services\StoreSaleableProducts;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;

class MerchandiseInventoryTransactionController extends Controller
{
    public function addToInventory(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $purchase->changeStatusToAddedToInventory();
            AddPurchasedItemsToInventory::addToInventory($purchase);
        });

        return redirect()->back();
    }

    public function subtractFromInventory($sale)
    {
        $sale = Sale::find($sale) ?? Gdn::find($sale);
        
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
