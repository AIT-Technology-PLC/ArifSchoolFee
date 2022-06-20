<?php

namespace App\View\Components\Common;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class NewProductList extends Component
{
    public $products;

    public function __construct()
    {
        $this->products = Cache::store('array')->rememberForever(auth()->id() . '_' . 'newProductLists', function () {
            return Product::select(['id', 'product_category_id', 'name', 'code'])
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
