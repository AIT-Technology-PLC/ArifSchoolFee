<?php

namespace App\View\Composers;

use App\Models\Product;
use Illuminate\View\View;

class ProductComposer
{
    protected $products;

    public function __construct()
    {
        $this->products = Product::query()
            ->with([
                'prices' => fn($q) => $q->active(),
            ])
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('taxes', 'products.tax_id', '=', 'taxes.id')
            ->select([
                'products.id',
                'products.name',
                'products.type',
                'products.code',
                'products.unit_of_measurement',
                'products.min_on_hand',
                'products.product_category_id',
                'products.is_batchable',
                'products.is_active',
                'products.is_active_for_sale',
                'products.is_active_for_purchase',
                'products.is_active_for_job',
                'products.is_product_single',
                'product_categories.name AS product_category_name',
                'taxes.type AS tax_name',
            ])
            ->selectRaw('taxes.amount + 1 AS tax_amount')
            ->orderBy('name')
            ->get();
    }

    public function compose(View $view)
    {
        $view->with('products', $this->products);
    }
}
