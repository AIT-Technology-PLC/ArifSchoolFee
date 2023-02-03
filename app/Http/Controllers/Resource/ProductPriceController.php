<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ProductPriceDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Price Management');
    }

    public function index(Product $product, ProductPriceDatatable $datatable)
    {
        $datatable->builder()->setTableId('product-price-datatable');

        return $datatable->render('products.prices.index', compact('product'));
    }

    public function edit(Price $price)
    {
        $prices = Price::where('product_id', $price->product_id)->get();

        return view('products.prices.edit', compact('prices'));
    }

    public function update(UpdatePriceRequest $request, Price $price)
    {
        $prices = collect($request->validated('price'));

        $product = $price->product;

        DB::transaction(function () use ($prices, $product) {
            $product->prices()->forceDelete();

            $product->prices()->createMany($prices);
        });

        return redirect()->route('products.prices.index', $product->id)->with('successMessage', 'Price updated successfully.');
    }
}