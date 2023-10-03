<?php

namespace App\Reports;

use App\Models\MerchandiseBatch;

class InventoryBatchReport
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
        $this->query = MerchandiseBatch::query()
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->when(isset($this->filters['availability']) && $this->filters['availability'] == 'available', fn($q) => $q->available())
            ->when(isset($this->filters['availability']) && $this->filters['availability'] == 'out_of_stock', fn($q) => $q->where('merchandise_batches.quantity', '=', 0))
            ->when(isset($this->filters['expiry']) && $this->filters['expiry'] == 'expired', fn($q) => $q->expired())
            ->when(isset($this->filters['expiry']) && $this->filters['expiry'] == 'usable', fn($q) => $q->notExpired())
            ->when(isset($this->filters['expiry']) && $this->filters['expiry'] == 'near_expired', fn($q) => $q->nearToBeExpired())
            ->when(isset($this->filters['product_id']), fn($q) => $q->where('product_id', $this->filters['product_id']))
            ->selectRaw('
                products.name AS name,
                products.code as code,
                products.unit_of_measurement as unit,
                merchandise_batches.quantity as quantity,
                merchandise_batches.batch_no as batch_no,
                merchandise_batches.expires_on as expires_on');
    }

    public function getInventoryBatchReports()
    {
        return (clone $this->query)->get();
    }
}
