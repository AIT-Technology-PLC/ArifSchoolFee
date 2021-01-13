<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Siv;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SivController extends Controller
{
    use PrependCompanyId;

    public function create()
    {
        $saleOrPurchases = Sale::with('saleDetails.product')->find(request('sale')) ??
        Purchase::with('purchaseDetails.product')->find(request('purchase'));

        $saleOrPurchases = $saleOrPurchases['saleDetails'] ?? $saleOrPurchases['purchaseDetails'];

        return view('sivs.create', compact('saleOrPurchases'));
    }

    public function store(Request $request)
    {
        $request['code'] = $this->prependCompanyId($request->code);

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

        $siv = DB::transaction(function () use ($basicSivData, $sivDetailsData) {
            $saleOrPurchase = Sale::find(request('sale')) ?? Purchase::find(request('purchase'));
            $siv = $saleOrPurchase->sivs()->create($basicSivData);
            $siv->sivDetails()->createMany($sivDetailsData);

            return $siv;
        });

        return request('sale') ?
        redirect()->route('sales.sivs', $siv->sivable->id) :
        redirect()->route('purchases.sivs', $siv->sivable->id);
    }

    public function edit(Siv $siv)
    {
        $siv->load(['sivDetails.product']);

        $saleOrPurchases = $siv->sivable['saleDetails'] ?? $siv->sivable['purchaseDetails'];

        $saleOrPurchases->load('product');

        return view('sivs.edit', compact('siv', 'saleOrPurchases'));
    }

    public function update(Request $request, Siv $siv)
    {
        $request['code'] = $this->prependCompanyId($request->code);

        $sivData = $request->validate([
            'code' => 'required|string|unique:sivs,code,' . $siv->id,
            'siv' => 'required|array',
            'siv.*.product_id' => 'required|integer',
            'siv.*.quantity' => 'required|numeric',
            'siv.*.description' => 'nullable|string',
        ]);

        $sivData['updated_by'] = auth()->user()->id;

        $basicSivData = Arr::except($sivData, 'siv');
        $sivDetailsData = $sivData['siv'];

        DB::transaction(function () use ($siv, $basicSivData, $sivDetailsData) {
            $siv->update($basicSivData);

            for ($i = 0; $i < count($sivDetailsData); $i++) {
                $siv->sivDetails[$i]->update($sivDetailsData[$i]);
            }
        });

        return str_contains($siv->sivable_type, 'Sale') ?
        redirect()->route('sales.sivs', $siv->sivable->id) :
        redirect()->route('purchases.sivs', $siv->sivable->id);
    }

    public function destroy(Siv $siv)
    {
        //
    }

    public function getSivsOfPurchase(Purchase $purchase)
    {
        $this->authorize('view', $purchase);

        $sivs = $purchase->sivs->load(['sivDetails.product', 'createdBy', 'updatedBy']);

        return view('sivs.show_sivs', compact('sivs'));
    }

    public function getSivsOfSale(Sale $sale)
    {
        $this->authorize('view', $sale);

        $sivs = $sale->sivs->load(['sivDetails.product', 'createdBy', 'updatedBy']);

        return view('sivs.show_sivs', compact('sivs'));
    }
}
