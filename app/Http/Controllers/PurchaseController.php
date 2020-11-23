<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

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

        $data['company_id'] = auth()->user()->employee->company_id;
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $this->purchase->create($data);

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

        return view('purchases.edit', compact('purhcase', 'products', 'suppliers'));
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
