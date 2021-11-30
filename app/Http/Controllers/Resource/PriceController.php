<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PriceDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
use App\Notifications\PriceUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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
        $prices = collect($request->validated()['price']);

        if ($prices->duplicates('product_id')->count()) {
            return back()->withInput()->with('failedMessage', 'One product can have only one price.');
        }

        DB::transaction(function () use ($prices) {
            foreach ($prices as $price) {
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

        $users = User::permission('Read Price')
            ->whereHas('employee', fn($query) => $query->where('company_id', userCompany()->id))
            ->where('id', '<>', auth()->id())
            ->get();

        Notification::send($users, new PriceUpdated($price));

        return redirect()->route('prices.index')->with('successMessage', 'Price updated successfully.');
    }

    public function destroy(Price $price)
    {
        $price->forceDelete();

        return back()->with('successMessage', 'Price deleted successfully.');
    }
}
