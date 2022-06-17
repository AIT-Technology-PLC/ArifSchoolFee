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

    public function index()
    {
        $products = Product::with(['productCategory', 'price'])->orderBy('name')->get();

        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->type,
                'code' => $product->code ?? '',
                'unit_of_measurement' => $product->unit_of_measurement,
                'min_on_hand' => $product->min_on_hand,

                'product_category_id' => $product->product_category_id,
                'product_category_name' => $product->productCategory->name,
                'price' => $product->price,
            ];
        });
    }

    public function show(Product $product)
    {
        $product->with('price:id,type,fixed_price,min_price,max_price');

        return collect([
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->product_category_id,
            'code' => $product->code ?? '',
            'min_on_hand' => $product->min_on_hand,
            'type' => $product->type,
            'unit_of_measurement' => $product->unit_of_measurement,
            'price_type' => $product->price ? $product->price->type : '',
            'price' => $product->price->fixed_price ?? $product->price->max_price ?? '.00',
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
