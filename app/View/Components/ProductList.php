<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\View\Component;

class ProductList extends Component
{
    public $products, $name, $selectedProductId;

    public function __construct($name, $selectedProductId)
    {
        $this->products = Product::companyProducts()
            ->select(['id', 'product_category_id', 'name', 'code'])
            ->with('productCategory')
            ->orderBy('name')
            ->get();

        $this->name = $name;

        $this->selectedProductId = $selectedProductId;
    }

    public function render()
    {
        return view('components.product-list');
    }
}
