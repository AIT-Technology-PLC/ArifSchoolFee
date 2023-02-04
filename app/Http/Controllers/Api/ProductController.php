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
        $products = Product::with(['productCategory', 'tax'])->orderBy('name')->get();

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
                'prices' => $product->prices()->active()->get(),
                'is_batchable' => $product->is_batchable,
                'is_active' => $product->is_active,
                'is_active_for_sale' => $product->is_active_for_sale,
                'is_active_for_purchase' => $product->is_active_for_purchase,
                'is_active_for_job' => $product->is_active_for_job,

                'tax_name' => $product->tax->type,
                'tax_amount' => $product->tax->amount + 1,
            ];
        });
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
