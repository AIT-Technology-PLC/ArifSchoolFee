<?php

namespace App\Reports;

use App\Models\PurchaseDetail;
use App\Models\TransferDetail;

class InventoryInTransitReport
{
    private $query;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $this->setQuery();
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    private function setQuery()
    {
        $this->query = $this->filters['transaction_type'] == 'purchases' ?
        PurchaseDetail::query()
            ->whereHas('purchase', fn($q) => $q->notPurchased()->approved()->withoutGlobalScopes([BranchScope::class]))
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->leftJoin('users', 'purchases.created_by', '=', 'users.id') :
        TransferDetail::query()
            ->whereHas('transfer', fn($q) => $q->subtracted()->notAdded()->withoutGlobalScopes([BranchScope::class]))
            ->join('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')
            ->join('products', 'transfer_details.product_id', '=', 'products.id')
            ->leftJoin('users', 'transfers.created_by', '=', 'users.id');
    }

    public function getInventoryInTransits()
    {
        return (clone $this->query)->get();
    }
}
