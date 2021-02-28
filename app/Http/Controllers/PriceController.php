<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PriceController extends Controller
{
    private $price;

    public function __construct(Price $price)
    {
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

    public function store(Request $request)
    {
        $priceData = $request->validate([
            'product_id' => 'required|integer',
            'price' => 'required|numeric|min:1',
        ]);

        $priceData['company_id'] = auth()->user()->employee->company_id;
        $priceData['created_by'] = auth()->user()->id;
        $priceData['updated_by'] = auth()->user()->id;

        $this->price->firstOrCreate(
            Arr::only($priceData, 'product_id'),
            Arr::except($priceData, 'product_id')
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

        $priceData['updated_by'] = auth()->user()->id;

        $price->update($priceData);

        return redirect()->route('prices.index');
    }
}
