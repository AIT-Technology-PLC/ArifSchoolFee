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

    public function __construct($type = [], $includedProducts = ['sales', 'purchases', 'jobs'])
    {
        $this->type = $type;

        $this->includedProducts = $includedProducts;

        $cacheName = str('newProductLists')->append(authUser()->id, 'newProductLists', implode($this->type))->toString();

        $this->products = Cache::store('array')->rememberForever($cacheName, function () {
            return Product::select(['id', 'product_category_id', 'name', 'code', 'type', 'description'])
                ->when(!empty($this->type), fn($q) => $q->whereIn('type', $this->type))
                ->when(in_array('sales', $this->includedProducts), fn($query) => $query->activeForSale())
                ->when(in_array('purchases', $this->includedProducts), fn($query) => $query->activeForPurchase())
                ->when(in_array('jobs', $this->includedProducts), fn($query) => $query->activeForJob())
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
