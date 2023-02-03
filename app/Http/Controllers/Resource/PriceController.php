<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PriceDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
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
        $datatable->builder()->setTableId('prices-datatable')->orderBy(1, 'asc');

        $totalProducts = Product::count();

        $totalWithPrice = Product::whereHas('prices')->count();

        $totalNoPrices = $totalProducts - $totalWithPrice;

        return $datatable->render('prices.index', compact('totalProducts', 'totalWithPrice', 'totalNoPrices'));
    }

    public function create()
    {
        return view('prices.create');
    }

    public function store(StorePriceRequest $request)
    {
        $prices = collect($request->validated('price'));

        DB::transaction(function () use ($prices) {
            foreach ($prices as $price) {
                Price::firstOrCreate($price);
            }
        });

        return redirect()->route('prices.index')->with('successMessage', 'New prices are added.');
    }

    public function destroy(Price $price)
    {
        $price->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
