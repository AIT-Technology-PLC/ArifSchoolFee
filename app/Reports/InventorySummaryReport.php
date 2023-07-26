<?php

namespace App\Reports;

use App\Models\InventoryHistory;

class InventorySummaryReport
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
        $this->query = InventoryHistory::query()
            ->join('products', 'inventory_histories.product_id', '=', 'products.id')
            ->join('warehouses', 'inventory_histories.warehouse_id', '=', 'warehouses.id')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('inventory_histories.warehouse_id', $this->filters['branches']))
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('inventory_histories.issued_on', '>=', $this->filters['period'][0])->whereDate('inventory_histories.issued_on', '<=', $this->filters['period'][1]))
            ->when(isset($this->filters['product_id']), fn($q) => $q->where('product_id', $this->filters['product_id']));
    }

    public function getGeneralSummaries()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(CASE WHEN is_subtract = 1 THEN  quantity END) AS outgoing,
                SUM(CASE WHEN is_subtract = 0 THEN  quantity END) AS incoming,
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement
                ')
            ->groupBy('branch_name', 'product_name', 'unit_of_measurement')
            ->get();
    }

    public function getDamageReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Damage')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getGrnReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Grn')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getAdjustmentReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Adjustment')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                CASE WHEN inventory_histories.is_subtract = 1 THEN -1 ELSE 1 END * inventory_histories.quantity AS quantity,
                inventory_histories.issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getProductionReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Job')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getTransferReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Transfer')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                CASE WHEN inventory_histories.is_subtract = 1 THEN "send" ELSE "received" END  AS operation,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getReturnReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Returnn')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getGdnReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Gdn')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getSaleReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Sale')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getReservationReports()
    {
        return (clone $this->query)
            ->where('model_type', 'App\Models\Reservation')
            ->selectRaw('
                warehouses.name AS branch_name,
                products.name AS product_name,
                products.unit_of_measurement AS unit_of_measurement,
                inventory_histories.quantity AS quantity,
                inventory_histories.issued_on AS issued_on
            ')
            ->orderByDesc('quantity')
            ->get();
    }
}
