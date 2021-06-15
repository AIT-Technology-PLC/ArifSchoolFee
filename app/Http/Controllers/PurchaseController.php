<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\PrependCompanyId;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    use PrependCompanyId;

    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Purchase Management');

        $this->authorizeResource(Purchase::class, 'purchase');

        $this->purchase = $purchase;
    }

    public function index()
    {
        $purchases = $this->purchase->getAll()->load(['createdBy', 'updatedBy', 'company', 'purchaseDetails']);

        $totalPurchases = $this->purchase->countPurchasesOfCompany();

        return view('purchases.index', compact('purchases', 'totalPurchases'));
    }

    public function create(Product $product, Supplier $supplier)
    {
        $products = $product->getProductNames();

        $suppliers = $supplier->getSupplierNames();

        $currentPurchaseNo = (Purchase::select('purchase_no')->companyPurchases()->latest()->first()->purchase_no) ?? 0;

        return view('purchases.create', compact('products', 'suppliers', 'currentPurchaseNo'));
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

    public function edit(Purchase $purchase, Product $product, Supplier $supplier)
    {
        $purchase->load('purchaseDetails.product');

        $products = $product->getProductNames();

        $suppliers = $supplier->getSupplierNames();

        return view('purchases.edit', compact('purchase', 'products', 'suppliers'));
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
