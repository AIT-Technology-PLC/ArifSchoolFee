<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\View\Component;

class ProductList extends Component
{
    public $products, $name, $selectedProductId, $tags;

    public function __construct($name, $selectedProductId, $tags)
    {
        $this->products = Product::select(['id', 'product_category_id', 'name', 'code'])
            ->with('productCategory')
            ->orderBy('name')
            ->get();

        $this->name = $name;

        $this->selectedProductId = $selectedProductId;

        $this->tags = $tags;
    }

    public function render()
    {
        return view('components.product-list');
    }
}
