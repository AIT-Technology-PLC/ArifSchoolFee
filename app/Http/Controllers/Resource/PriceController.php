<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PriceDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Price Management');

        $this->authorizeResource(Price::class, 'price');
    }

    public function index(PriceDatatable $datatable)
    {
        $totalProducts = Product::count();

        $totalFixedPrices = Price::fixed()->count();

        $totalRangePrices = Price::range()->count();

        $totalNoPrices = Product::count() - ($totalFixedPrices + $totalRangePrices);

        return $datatable->render('prices.index', compact('totalProducts', 'totalFixedPrices', 'totalRangePrices', 'totalNoPrices'));
    }

    public function create()
    {
        $excludedProducts = Product::has('price')->get('id');

        return view('prices.create', compact('excludedProducts'));
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
        $price->load('product');

        return view('prices.edit', compact('price'));
    }

    public function update(UpdatePriceRequest $request, Price $price)
    {
        $price->update($request->validated());

        return redirect()->route('prices.index')->with('successMessage', 'Price updated successfully');
    }

    public function destroy(Price $price)
    {
        abort_if(auth()->user()->cannot('Delete Price'), 403);

        $price->forceDelete();

        return back()->with('successMessage', 'Price deleted successfully.');
    }
}
