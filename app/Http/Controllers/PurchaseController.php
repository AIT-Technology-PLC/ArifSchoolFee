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
        return view('purchases.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Purchase $purchase)
    {
        //
    }

    public function edit(Purchase $purchase, Product $product, Supplier $supplier)
    {
        //
    }

    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    public function destroy(Purchase $purchase)
    {
        //
    }
}
