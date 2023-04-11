<?php

namespace App\Reports;

use App\Models\PurchaseDetail;
use App\Scopes\BranchScope;

class PurchaseReport
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
        $this->query = PurchaseDetail::query()
            ->whereHas('purchase', fn($q) => $q->purchased()->withoutGlobalScopes([BranchScope::class]))
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->join('warehouses', 'purchases.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('taxes', 'purchases.tax_id', '=', 'taxes.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('purchases.warehouse_id', $this->filters['branches']))
            ->when(isset($this->filters['period']), fn($q) => $q->whereDate('purchases.purchased_on', '>=', $this->filters['period'][0])->whereDate('purchases.purchased_on', '<=', $this->filters['period'][1]))
            ->when(isset($this->filters['supplier_id']), fn($q) => $q->where('purchases.supplier_id', $this->filters['supplier_id']))
            ->when(isset($this->filters['tax_id']), fn($q) => $q->where('purchases.tax_id', $this->filters['tax_id']))
            ->where('purchases.type', 'Local Purchase');
    }

    public function getPurchaseCount()
    {
        return (clone $this->query)->distinct('purchase_id')->count();
    }

    public function getTotalPurchaseAfterTax()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)
                ) AS expense
               ')
            ->where('purchases.type', 'Local Purchase')
            ->first()
            ->expense;
    }

    public function getAverageItemsPerPurchase()
    {
        if ($this->getPurchaseCount == 0) {
            return $this->getPurchaseCount;
        }

        return (clone $this->query)->count() / $this->getPurchaseCount;
    }

    public function getAveragePurchaseValue()
    {
        if ($this->getPurchaseCount == 0) {
            return $this->getPurchaseCount;
        }

        return $this->getTotalPurchaseAfterTax / $this->getPurchaseCount;
    }

    public function getPaymentTypesByPurchase()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)
                ) AS expense,
                COUNT(DISTINCT purchase_id) AS transactions,
                purchases.payment_type AS payment_type
            ')
            ->groupBy('payment_type')
            ->orderByDesc('expense')
            ->get();
    }

    public function getPurchasesByType()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)
                ) AS expense,
                COUNT(DISTINCT purchase_id) AS transactions,
                purchases.type AS type
            ')
            ->groupBy('type')
            ->orderByDesc('expense')
            ->get();
    }

    public function getProductsByPurchase()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)
                ) AS expense,
                products.name AS product_name,
                SUM(quantity) AS quantity
            ')
            ->groupBy('product_name')
            ->orderByDesc('expense')
            ->get();
    }

    public function getProductCategoriesByPurchase()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)
                ) AS expense,
                product_categories.name AS product_category_name,
                SUM(quantity) AS quantity
            ')
            ->groupBy('product_category_name')
            ->orderByDesc('quantity')
            ->get();
    }

    public function getBranchesByPurchase()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(quantity*unit_price*(1+taxes.amount)
                ) AS expense,
                warehouses.name AS branch_name')
            ->groupBy('branch_name')
            ->orderByDesc('expense')
            ->get();
    }
}