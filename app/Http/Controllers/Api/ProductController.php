<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');
    }

    public function show(Product $product)
    {
        $product->with('prices:id,fixed_price');

        return collect([
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->product_category_id,
            'code' => $product->code ?? '',
            'min_on_hand' => $product->min_on_hand,
            'type' => $product->type,
            'unit_of_measurement' => $product->unit_of_measurement,
            'prices' => $product->prices()->active()->get(),
        ]);
    }

    public function getproductsByCategory(ProductCategory $category)
    {
        return $category
            ->products()
            ->with('productCategory:id,name')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => $product->name,
                    'code' => $product->code ?? '',
                    'category' => $product->productCategory->name,
                ];
            });
    }
}
