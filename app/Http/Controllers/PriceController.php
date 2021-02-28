<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Product;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    private $price;

    public function __construct(Price $price)
    {
        $this->price = $price;
    }

    public function index(Price $price)
    {
        $prices = $price->load(['product', 'updatedBy']);

        return view('prices.index', compact('prices'));
    }

    public function create(Product $product)
    {

    }

    public function store(Request $request, Price $price)
    {

    }

    public function edit(Price $price)
    {
        //
    }

    public function update(Request $request, Price $price)
    {
        //
    }
}
