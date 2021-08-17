<?php

namespace App\View\Components;

use App\Models\Product;
use Illuminate\View\Component;

class ProductList extends Component
{
    public $products, $model;

    public function __construct($model)
    {
        $this->products = Product::companyProducts()
            ->select(['id', 'product_category_id', 'name', 'code'])
            ->with('productCategory')
            ->orderBy('name')
            ->get();

        $this->model = $model;
    }

    public function render()
    {
        return view('components.product-list');
    }
}
