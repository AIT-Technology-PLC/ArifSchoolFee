<?php

namespace App\Reports;

use App\Models\TransferDetail;

class InventoryTransferReport
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
        $this->query = TransferDetail::query()
            ->whereHas('transfer', fn($q) => $q->added()->withoutGlobalScopes([BranchScope::class]))
            ->join('transfers', 'transfer_details.transfer_id', '=', 'transfers.id')
            ->join('products', 'transfer_details.product_id', '=', 'products.id')
            ->leftJoin('users', 'transfers.created_by', '=', 'users.id')
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('transfers.issued_on', '>=', $this->filters['period'][0])->whereDate('transfers.issued_on', '<=', $this->filters['period'][1]))
            ->when(isset($this->filters['user_id']), fn($q) => $q->where('transfers.created_by', $this->filters['user_id']))
            ->when(isset($this->filters['from']), fn($q) => $q->where('transfers.transferred_from', $this->filters['from']))
            ->when(isset($this->filters['to']), fn($q) => $q->where('transfers.transferred_to', $this->filters['to']))
            ->when(isset($this->filters['product_id']), fn($q) => $q->where('product_id', $this->filters['product_id']));
    }

    public function getInventoryTransfers()
    {
        return (clone $this->query)->get();

    }
}