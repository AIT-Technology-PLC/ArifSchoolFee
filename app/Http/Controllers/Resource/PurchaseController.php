<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\NextReferenceNumService;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Purchase Management');

        $this->authorizeResource(Purchase::class, 'purchase');
    }

    public function index()
    {
        $purchases = Purchase::with(['createdBy', 'updatedBy', 'purchaseDetails'])->latest()->get();

        $totalPurchases = Purchase::count();

        return view('purchases.index', compact('purchases', 'totalPurchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $currentPurchaseNo = NextReferenceNumService::table('purchases');

        return view('purchases.create', compact('suppliers', 'currentPurchaseNo'));
    }

    public function store(StorePurchaseRequest $request)
    {
        $purchase = DB::transaction(function () use ($request) {
            $purchase = Purchase::create($request->except('purchase'));

            $purchase->purchaseDetails()->createMany($request->purchase);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase->id);
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['purchaseDetails.product', 'supplier', 'grns']);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
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

        return back()->with('deleted', 'Deleted successfully.');
    }
}
