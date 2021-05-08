<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\AddPurchasedItemsToInventory;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    use PrependCompanyId;

    private $purchase;

    public function __construct(Purchase $purchase)
    {
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

            if (!$purchase->isPurchaseManual()) {
                AddPurchasedItemsToInventory::addToInventory($purchase);
            }

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

    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->isAddedToInventory()) {
            return redirect()->route('purchases.show', $purchase->id);
        }

        $request['purchase_no'] = $this->prependCompanyId($request->purchase_no);

        $purchaseData = $request->validate([
            'purchase_no' => 'required|string|unique:purchases,purchase_no,' . $purchase->id,
            'purchase' => 'required|array',
            'type' => 'required|string',
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
            'supplier_id' => 'nullable|integer',
            'purchased_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $purchaseData['updated_by'] = auth()->id();

        $basicPurchaseData = Arr::except($purchaseData, 'purchase');
        $purchaseDetailsData = $purchaseData['purchase'];

        DB::transaction(function () use ($purchase, $basicPurchaseData, $purchaseDetailsData) {
            $purchase->update($basicPurchaseData);

            for ($i = 0; $i < count($purchaseDetailsData); $i++) {
                $purchase->purchaseDetails[$i]->update($purchaseDetailsData[$i]);
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
