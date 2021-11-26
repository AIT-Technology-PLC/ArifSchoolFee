<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;

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
        //
    }

    public function store(StorePriceRequest $request)
    {
        //
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
