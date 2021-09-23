<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->middleware('isFeatureAccessible:Purchase Management');

        $this->authorizeResource(Purchase::class, 'purchase');

        $this->purchase = $purchase;
    }

    public function index()
    {
        $purchases = $this->purchase->getAll()->load(['createdBy', 'updatedBy', 'company', 'purchaseDetails']);

        $totalPurchases = $purchases->count();

        return view('purchases.index', compact('purchases', 'totalPurchases'));
    }

    public function create(Supplier $supplier)
    {
        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $currentPurchaseNo = Purchase::byBranch()->max('code') + 1;

        return view('purchases.create', compact('suppliers', 'currentPurchaseNo'));
    }

    public function store(StorePurchaseRequest $request)
    {
        $purchase = DB::transaction(function () use ($request) {
            $purchase = $this->purchase->create($request->except('purchase'));

            $purchase->purchaseDetails()->createMany($request->purchase);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase->id);
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['purchaseDetails.product', 'supplier', 'company', 'grns']);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase, Supplier $supplier)
    {
        $purchase->load('purchaseDetails.product');

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::transaction(function () use ($request, $purchase) {
            $purchase->update($request->except('purchase'));

            for ($i = 0; $i < count($request->purchase); $i++) {
                $purchase->purchaseDetails[$i]->update($request->purchase[$i]);
            }
        });

        return redirect()->route('purchases.show', $purchase->id);
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
