<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Siv;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SivController extends Controller
{
    private $siv;

    public function __construct(Siv $siv)
    {
        $this->siv = $siv;
    }

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
            'code' => 'required|string|unique:sivs',
            'siv' => 'required|array',
            'siv.*.product_id' => 'required|integer',
            'siv.*.quantity' => 'required|numeric',
            'siv.*.description' => 'nullable|string',
        ]);

        $sivData['company_id'] = auth()->user()->employee->company_id;
        $sivData['created_by'] = auth()->user()->id;
        $sivData['updated_by'] = auth()->user()->id;

        $basicSivData = Arr::except($sivData, 'siv');
        $sivDetailsData = $sivData['siv'];

        DB::transaction(function () use ($basicSivData, $sivDetailsData) {
            $saleOrPurchase = Sale::find(request('sale')) ?? Purchase::find(request('purchase'));
            $siv = $saleOrPurchase->sivs()->create($basicSivData);
            $siv->sivDetails()->createMany($sivDetailsData);
        });
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
