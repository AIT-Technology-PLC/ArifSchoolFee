<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceIncrementRequest;
use App\Http\Requests\UpdatePriceIncrementRequest;
use App\Models\PriceIncrement;

class PriceIncrementController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Price Increment');

        $this->authorizeResource(PriceIncrement::class);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StorePriceIncrementRequest $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit(PriceIncrement $priceIncrement)
    {
        //
    }

    public function update(UpdatePriceIncrementRequest $request, PriceIncrement $priceIncrement)
    {
        //
    }

    public function destroy(PriceIncrement $priceIncrement)
    {
        //
    }
}