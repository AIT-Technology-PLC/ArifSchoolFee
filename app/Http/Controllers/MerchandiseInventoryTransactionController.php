<?php

namespace App\Http\Controllers;

use App\Models\Gdn;
use App\Models\Grn;
use App\Models\Purchase;
use App\Models\Sale;
use App\Notifications\GdnSubtracted;
use App\Notifications\GrnAdded;
use App\Services\AddPurchasedItemsToInventory;
use App\Services\StoreSaleableProducts;
use App\Traits\NotifiableUsers;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class MerchandiseInventoryTransactionController extends Controller
{
    use NotifiableUsers;

    public function addToInventory($purchase)
    {
        if (!auth()->user()->hasPermissionTo('Add GRN')) {
            return new AuthorizationException();
        }

        $purchase = Purchase::find($purchase) ?? Grn::find($purchase);

        if ($purchase->getTable() == 'grns' && !$purchase->isGrnApproved()) {
            return redirect()->back()->with('message', 'This GRN is not approved');
        }

        DB::transaction(function () use ($purchase) {
            $purchase->changeStatusToAddedToInventory();
            AddPurchasedItemsToInventory::addToInventory($purchase);
        });

        Notification::send($this->notifiableUsers('Approve GRN'), new GrnAdded($purchase));
        Notification::send($this->notifyCreator($purchase, $this->notifiableUsers('Approve GRN')), new GrnAdded($purchase));

        return redirect()->back();
    }

    public function subtractFromInventory($sale)
    {
        if (!auth()->user()->hasPermissionTo('Subtract GDN')) {
            return new AuthorizationException();
        }

        if (request('model') == 'gdns') {
            $sale = Gdn::find($sale);
        }

        if (request('model') == 'sales') {
            $sale = Sale::find($sale);
        }

        if ($sale->getTable() == 'gdns' && !$sale->isGdnApproved()) {
            return redirect()->back()->with('message', 'This DO/GDN is not approved');
        }

        $isSaleValid = DB::transaction(function () use ($sale) {
            $sale->changeStatusToSubtractedFromInventory();
            $isSaleValid = StoreSaleableProducts::storeSoldProducts($sale);

            if (!$isSaleValid) {
                DB::rollback();
            }

            return $isSaleValid;
        });

        if ($sale->getTable() == 'gdns' && $isSaleValid && $sale->isGdnSubtracted()) {
            Notification::send($this->notifiableUsers('Approve GDN'), new GdnSubtracted($sale));
            Notification::send($this->notifyCreator($sale, $this->notifiableUsers('Approve GDN')), new GdnSubtracted($sale));
        }

        return redirect()->back();
    }
}
