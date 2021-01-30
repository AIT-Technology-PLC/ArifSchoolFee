<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
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

    public function create(Product $product, Supplier $supplier, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $suppliers = $supplier->getSupplierNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('purchases.create', compact('products', 'suppliers', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request['purchase_no'] = $this->prependCompanyId($request->purchase_no);

        $purchaseData = $request->validate([
            'is_manual' => 'required|integer',
            'purchase_no' => 'required|string|unique:purchases',
            'purchase' => 'required|array',
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
            'supplier_id' => 'nullable|integer',
            'status' => 'sometimes|required|string|max:255',
            'purchased_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $purchaseData['company_id'] = auth()->user()->employee->company_id;
        $purchaseData['created_by'] = auth()->user()->id;
        $purchaseData['updated_by'] = auth()->user()->id;

        $basicPurchaseData = Arr::except($purchaseData, 'purchase');
        $purchaseDetailsData = $purchaseData['purchase'];

        $purchase = DB::transaction(function () use ($basicPurchaseData, $purchaseDetailsData) {
            $purchase = $this->purchase->create($basicPurchaseData);
            $purchase->purchaseDetails()->createMany($purchaseDetailsData);

            if (!$purchase->isPurchaseManual()) {
                AddPurchasedItemsToInventory::addToInventory($purchase);
            }

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase->id);
    }

    public function show(Purchase $purchase, Warehouse $warehouse)
    {
        $purchase->load(['purchaseDetails.product', 'supplier', 'company']);

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('purchases.show', compact('purchase', 'warehouses'));
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
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
            'supplier_id' => 'nullable|integer',
            'purchased_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $purchaseData['updated_by'] = auth()->user()->id;

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
