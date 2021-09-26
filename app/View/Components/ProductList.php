<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class ProductList extends Component
{
    public $products, $name, $selectedProductId, $tags;

    public function __construct($name, $selectedProductId, $tags)
    {
        $this->products = Cache::store('array')->rememberForever('productLists', function () {
            return Product::select(['id', 'product_category_id', 'name', 'code'])
                ->with('productCategory:id,name')
                ->orderBy('name')
                ->get();
        });

        $this->name = $name;

        $this->selectedProductId = $selectedProductId;

        $this->tags = $tags;
    }

    public function render()
    {
        return view('components.product-list');
    }
}
