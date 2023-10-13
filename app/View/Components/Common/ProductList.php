<?php

namespace App\View\Components\Common;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class ProductList extends Component
{
    public $products;

    public $name;

    public $selectedProductId;

    public $tags;

    public $excludedProducts;

    public $key;

    public function __construct(
        $name = null,
        $selectedProductId = null,
        $tags = false,
        $excludedProducts = null,
        $key = '[product_id]'
    ) {
        $this->name = $name;

        $this->selectedProductId = $selectedProductId;

        $this->tags = $tags;

        $this->excludedProducts = $excludedProducts;

        $this->key = $key;

        $this->products = Cache::store('array')->rememberForever(authUser()->id . '_' . 'productLists', function () {
            return Product::active()->where('is_product_single', 1)
                ->select(['id', 'product_category_id', 'name', 'code'])
                ->when($this->excludedProducts, fn($query) => $query->whereNotIn('id', $this->excludedProducts->toArray()))
                ->with('productCategory:id,name')
                ->orderBy('name')
                ->get();
        });
    }

    public function render()
    {
        return view('components.common.product-list');
    }
}
