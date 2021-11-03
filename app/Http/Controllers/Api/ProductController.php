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

    public function getProductUOM(Product $product)
    {
        return $product->unit_of_measurement;
    }
}
