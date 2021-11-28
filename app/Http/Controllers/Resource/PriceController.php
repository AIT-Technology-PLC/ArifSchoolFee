<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Price Management');

        $this->authorizeResource(Price::class, 'price');
    }

    public function index()
    {
        //
    }

    public function create()
    {
        return view('prices.create');
    }

    public function store(StorePriceRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated()['price'] as $price) {
                Price::firstOrCreate(['product_id' => $price['product_id']], $price);
            }
        });

        return redirect()->route('prices.index')->with('successMessage', 'New prices are added.');
    }

    public function edit(Price $price)
    {
        //
    }

    public function update(UpdatePriceRequest $request, Price $price)
    {
        //
    }

    public function destroy(Price $price)
    {
        //
    }
}
