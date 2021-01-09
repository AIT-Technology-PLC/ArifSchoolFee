<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Siv;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SivController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Product $product)
    {
        $products = $product->getProductNames();

        return view('sivs.create', compact('products'));
    }

    public function store(Request $request)
    {
        $sivData = $request->validate([
            'code' => 'required|string',
            'siv' => 'required|array',
            'siv.*.product_id' => 'required|integer',
            'siv.*.quantity' => 'required|numeric',
            'siv.*.description' => 'nullable|string'
        ]);

        $basicSivData = Arr::except($sivData, 'siv');
        $sivDetailsData = $sivData['siv'];
    }

    public function show(Siv $siv)
    {
        //
    }

    public function edit(Siv $siv)
    {
        //
    }

    public function update(Request $request, Siv $siv)
    {
        //
    }

    public function destroy(Siv $siv)
    {
        //
    }
}
