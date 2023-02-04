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
        $this->authorize('viewAny', Price::class);

        $datatable->builder()->setTableId('product-price-datatable');

        $price = $product->prices()->first();

        return $datatable->render('products.prices.index', compact('product', 'price'));
    }

    public function edit(Price $price)
    {
        $this->authorize('update', $price);

        $prices = Price::where('product_id', $price->product_id)->get();

        $productId = $price->product_id;

        return view('products.prices.edit', compact('price', 'prices', 'productId'));
    }

    public function update(UpdatePriceRequest $request, Price $price)
    {
        $this->authorize('update', $price);

        $product = $price->product;

        DB::transaction(function () use ($request, $product) {
            $product->prices()->forceDelete();

            $product->prices()->createMany($request->validated('price'));
        });

        return redirect()->route('products.prices.index', $product->id)->with('successMessage', 'Price updated successfully.');
    }
}
