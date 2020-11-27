<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
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

    public function create(Product $product, Supplier $supplier)
    {
        $products = $product->getProductNames();

        $suppliers = $supplier->getSupplierNames();

        return view('purchases.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $basicPurchaseData = $request->validate([
            'shipping_line' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $basicPurchaseData['company_id'] = auth()->user()->employee->company_id;
        $basicPurchaseData['created_by'] = auth()->user()->id;
        $basicPurchaseData['updated_by'] = auth()->user()->id;

        $purchaseDetailsData = $request->validate([
            'purchase' => 'required|array',
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.supplier_id' => 'nullable|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
        ]);

        $purchaseDetailsData = $purchaseDetailsData['purchase'];

        DB::transaction(function () use ($basicPurchaseData, $purchaseDetailsData) {
            $purchase = $this->purchase->create($basicPurchaseData);

            foreach ($purchaseDetailsData as $singlePurchaseDetailsData) {
                $purchase->purchaseDetails()->create($singlePurchaseDetailsData);
            }
        });

        return redirect()->route('purchases.index');
    }

    public function show(Purchase $purchase)
    {
        //
    }

    public function edit(Purchase $purchase, Product $product, Supplier $supplier)
    {
        $products = $product->getProductNames();

        $suppliers = $supplier->getSupplierNames();

        return view('purchases.edit', compact('purchase', 'products', 'suppliers'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $basicPurchaseData = $request->validate([
            'shipping_line' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $basicPurchaseData['updated_by'] = auth()->user()->id;

        $purchaseDetailsData = $request->validate([
            'purchase' => 'required|array',
            'purchase.*.product_id' => 'required|integer',
            'purchase.*.supplier_id' => 'nullable|integer',
            'purchase.*.quantity' => 'required|numeric',
            'purchase.*.unit_price' => 'required|numeric',
        ]);

        $purchaseDetailsData = $purchaseDetailsData['purchase'];

        DB::transaction(function () use ($purchase, $basicPurchaseData, $purchaseDetailsData) {
            $purchase->update($basicPurchaseData);

            foreach ($purchaseDetailsData as $singlePurchaseDetailsData) {
                $purchase->purchaseDetails()->update($singlePurchaseDetailsData);
            }
        });

        return redirect()->route('purchases.index');
    }

    public function destroy(Purchase $purchase)
    {
        //
    }
}
