<?php

namespace App\Reports;

use App\Models\PurchaseDetail;

class SupplierReport
{
    private $query;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters ?? null;

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
            ->whereHas('purchase', function ($q) {
                return $q->where('supplier_id', $this->filters)
                    ->when(isset($this->filters['period']), fn($q) => $q->whereDate('purchased_on', '>=', $this->filters['period'][0])->whereDate('purchased_on', '<=', $this->filters['period'][1]))
                    ->purchased();
            })
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id');
    }

    public function getPurchaseCount()
    {
        return (clone $this->query)->distinct('purchase_id')->count();
    }

    public function getTotalPurchaseExpenseAfterTax()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(
                    CASE
                        WHEN purchases.tax_type = "VAT" THEN quantity*unit_price*1.15
                        WHEN purchases.tax_type = "TOT" THEN quantity*unit_price*1.02
                        ELSE quantity*unit_price
                    END
                ) AS expense
               ')
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

    public function getAveragePurchaseExpenseValue()
    {
        if ($this->getPurchaseCount == 0) {
            return $this->getPurchaseCount;
        }

        return $this->getTotalPurchaseExpenseAfterTax / $this->getPurchaseCount;
    }

    public function getPaymentTypesByPurchase()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(
                    CASE
                        WHEN purchases.tax_type = "VAT" THEN quantity*unit_price*1.15
                        WHEN purchases.tax_type = "TOT" THEN quantity*unit_price*1.02
                        ELSE quantity*unit_price
                    END
                ) AS expense,
                COUNT(payment_type) AS transactions, payment_type')
            ->groupBy('payment_type')
            ->orderByDesc('expense')
            ->get();
    }

    public function getPurchasesByType()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(
                    CASE
                        WHEN purchases.tax_type = "VAT" THEN quantity*unit_price*1.15
                        WHEN purchases.tax_type = "TOT" THEN quantity*unit_price*1.02
                        ELSE quantity*unit_price
                    END
                ) AS expense,
                COUNT(purchases.type) AS transactions, purchases.type')
            ->groupBy('purchases.type')
            ->orderByDesc('expense')
            ->get();
    }

    public function getProductsByPurchaseExpense()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(
                    CASE
                        WHEN purchases.tax_type = "VAT" THEN quantity*unit_price*1.15
                        WHEN purchases.tax_type = "TOT" THEN quantity*unit_price*1.02
                        ELSE quantity*unit_price
                    END
                ) AS expense, products.name AS product_name, SUM(quantity) AS quantity')
            ->groupBy('product_name')
            ->orderByDesc('expense')
            ->get();
    }

    public function getProductCategoriesByPurchaseExpense()
    {
        return (clone $this->query)
            ->selectRaw('
                SUM(
                    CASE
                        WHEN purchases.tax_type = "VAT" THEN quantity*unit_price*1.15
                        WHEN purchases.tax_type = "TOT" THEN quantity*unit_price*1.02
                        ELSE quantity*unit_price
                    END
                ) AS expense, product_categories.name AS product_category_name, SUM(quantity) AS quantity')
            ->groupBy('product_category_name')
            ->orderByDesc('quantity')
            ->get();
    }
}