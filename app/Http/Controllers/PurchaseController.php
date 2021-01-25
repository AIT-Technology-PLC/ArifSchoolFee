<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\AddPurchasedItemsToInventory;
use App\Traits\HasOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    use HasOptions;

    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->authorizeResource(Purchase::class, 'purchase');

        $this->purchase = $purchase;
    }

    public function index()
    {
        $purchases = $this->purchase->getAll();

        $totalPurchases = $this->purchase->countPurchasesOfCompany();

        return view('purchases.index', compact('purchases', 'totalPurchases'));
    }

    public function create(Product $product, Supplier $supplier, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $suppliers = $supplier->getSupplierNames();

        $shippingLines = $this->getShippingLines();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('purchases.create', compact('products', 'suppliers', 'shippingLines', 'warehouses'));
    }

    public function store(Request $request)
    {
        $purchaseData = $request->validate([
            'purchase' => 'required|array',
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
            'supplier_id' => 'nullable|integer',
            'shipping_line' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'purchased_on' => 'required|date',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $purchaseData['company_id'] = auth()->user()->employee->company_id;
        $purchaseData['created_by'] = auth()->user()->id;
        $purchaseData['updated_by'] = auth()->user()->id;

        $basicPurchaseData = Arr::except($purchaseData, 'purchase');
        $purchaseDetailsData = $purchaseData['purchase'];

        DB::transaction(function () use ($basicPurchaseData, $purchaseDetailsData) {
            $purchase = $this->purchase->create($basicPurchaseData);
            $purchase->purchaseDetails()->createMany($purchaseDetailsData);
            AddPurchasedItemsToInventory::addToInventory($purchase);
        });

        return redirect()->route('purchases.index');
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

        $shippingLines = $this->getShippingLines();

        return view('purchases.edit', compact('purchase', 'products', 'suppliers', 'shippingLines'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->isAddedToInventory()) {
            return redirect()->route('purchases.show', $purchase->id);
        }

        $purchaseData = $request->validate([
            'purchase' => 'required|array',
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
            'supplier_id' => 'nullable|integer',
            'shipping_line' => 'nullable|string|max:255',
            'purchased_on' => 'required|date',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
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
