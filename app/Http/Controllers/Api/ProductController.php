<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');
    }

    public function show(Product $product)
    {
        $product->with('price:id,type,fixed_price,min_price,max_price');

        return collect([
            'name' => $product->name,
            'code' => $product->code ?? '',
            'min_on_hand' => $product->min_on_hand,
            'type' => $product->type,
            'unit_of_measurement' => $product->unit_of_measurement,
            'price_type' => $product->price ? $product->price->type : '',
            'price' => $product->price->fixed_price ?? $product->price->max_price ?? '.00',
        ]);
    }
}
