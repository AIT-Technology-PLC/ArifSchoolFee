<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use App\Models\Product;

class PriceController extends Controller
{
    private $price;

    public function __construct(Price $price)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Price Management');

        $this->authorizeResource(Price::class, 'price');

        $this->price = $price;
    }

    public function index(Price $price)
    {
        $prices = $price->getAll()->load(['product', 'createdBy', 'updatedBy']);

        return view('prices.index', compact('prices'));
    }

    public function create(Product $product)
    {
        $products = $product->getSaleableProducts();

        return view('prices.create', compact('products'));
    }

    public function store(StorePriceRequest $request)
    {
        $this->price->firstOrCreate(
            $request->only(['product_id', 'company_id']),
            $request->except(['product_id', 'company_id']),
        );

        return redirect()->route('prices.index');
    }

    public function edit(Price $price)
    {
        return view('prices.edit', compact('price'));
    }

    public function update(UpdatePriceRequest $request, Price $price)
    {
        $price->update($request->all());

        return redirect()->route('prices.index');
    }
}
