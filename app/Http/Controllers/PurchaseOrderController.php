<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    use PrependCompanyId;

    private $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    public function index()
    {

    }

    public function create(Product $product, Customer $customer)
    {
        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        return view('purchase_orders.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
