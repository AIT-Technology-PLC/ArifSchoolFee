<?php

namespace App\View\Components\Common;

use App\Models\Product;
use Illuminate\View\Component;

class NewProductList extends Component
{
    public $products;

    public $includedProducts;

    public $onlyNonBatchables;

    public $inventoryType;

    public $rawMaterial;

    public $finishedGoods;

    public function __construct($includedProducts = null, $onlyNonBatchables = false, $inventoryType = false, $rawMaterial = false, $finishedGoods = false)
    {
        $this->includedProducts = $includedProducts;

        $this->onlyNonBatchables = $onlyNonBatchables;

        $this->inventoryType = $inventoryType;

        $this->rawMaterial = $rawMaterial;

        $this->finishedGoods = $finishedGoods;

        $this->products = Product::active()
            ->select(['id', 'product_category_id', 'name', 'code', 'type', 'description'])
            ->when($this->onlyNonBatchables, fn($query) => $query->nonBatchable())
            ->when($this->inventoryType, fn($q) => $q->inventoryType())
            ->when($this->rawMaterial, fn($q) => $q->rawMaterial())
            ->when($this->finishedGoods, fn($q) => $q->finishedGoods())
            ->when($this->includedProducts == 'sales', fn($query) => $query->activeForSale())
            ->when($this->includedProducts == 'purchases', fn($query) => $query->activeForPurchase())
            ->when($this->includedProducts == 'jobs', fn($query) => $query->activeForJob())
            ->with('productCategory:id,name')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('components.common.new-product-list');
    }
}
