<?php

namespace App\View\Components\Common;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class NewProductList extends Component
{
    public $products;

    public $includedProducts;

    public $type;

    public $onlyNonBatchables;

    public function __construct($type = [], $includedProducts = null, $onlyNonBatchables = false)
    {
        $this->type = $type;

        $this->includedProducts = $includedProducts;

        $this->onlyNonBatchables = $onlyNonBatchables;

        $cacheName = str('newProductLists')->append(authUser()->id, 'newProductLists', implode($this->type))->toString();

        $this->products = Cache::store('array')->rememberForever($cacheName, function () {
            return Product::active()
                ->select(['id', 'product_category_id', 'name', 'code', 'type', 'description'])
                ->when($this->onlyNonBatchables, fn($query) => $query->nonBatchable())
                ->when(!empty($this->type), fn($q) => $q->whereIn('type', $this->type))
                ->when($this->includedProducts == 'sales', fn($query) => $query->activeForSale())
                ->when($this->includedProducts == 'purchases', fn($query) => $query->activeForPurchase())
                ->when($this->includedProducts == 'jobs', fn($query) => $query->activeForJob())
                ->with('productCategory:id,name')
                ->orderBy('name')
                ->get();
        });
    }

    public function render()
    {
        return view('components.common.new-product-list');
    }
}
