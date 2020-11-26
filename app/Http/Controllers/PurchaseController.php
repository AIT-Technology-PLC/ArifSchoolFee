<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $purchaseData = $request->validate([
            'purchase' => 'required|array',
            'purchase.product_id.*' => 'required|integer',
            'purchase.supplier_id.*' => 'nullable|integer',
            'purchase.quantity.*' => 'required|numeric',
            'purchase.unit_price.*' => 'required|numeric',
            'shipping_line' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $purchaseData['company_id'] = auth()->user()->employee->company_id;
        $purchaseData['created_by'] = auth()->user()->id;
        $purchaseData['updated_by'] = auth()->user()->id;

        $purchaseDataWithoutKeyPurchase = Arr::except($purchaseData, 'purchase');
        $purchaseDataWithOnlyKeyPurchase = Arr::only($purchaseData, 'purchase');

        DB::transaction(function () use ($purchaseDataWithoutKeyPurchase, $purchaseDataWithOnlyKeyPurchase) {
            $purchase = $this->purchase->create($purchaseDataWithoutKeyPurchase);

            foreach ($purchaseDataWithOnlyKeyPurchase['purchase'] as $key => $value) {
                for ($i = 0; $i < count($value); $i++) {
                    $purchaseDetailData[$i][$key] = $value[$i];
                    $purchaseDetailData[$i]['purchase_id'] = $purchase->id;
                }
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
        $data = $request->validate([
            'product_id' => 'required|integer',
            'supplier_id' => 'nullable|integer',
            'total_quantity' => 'required|numeric',
            'total_price' => 'nullable|numeric',
            'shipping_line' => 'required|string|max:255',
            'payment_status' => 'required|string|max:255',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $purchase->update($data);

        return redirect()->route('purchases.index');
    }

    public function destroy(Purchase $purchase)
    {
        //
    }
}
