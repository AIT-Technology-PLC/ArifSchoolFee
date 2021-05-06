<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriceRequest;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    private $price;

    public function __construct(Price $price)
    {
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

    public function update(Request $request, Price $price)
    {
        $priceData = $request->validate([
            'product_id' => 'required|integer',
            'price' => 'required|numeric|min:1',
        ]);

        $priceData['updated_by'] = auth()->id();

        $price->update($priceData);

        return redirect()->route('prices.index');
    }
}
