<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    private $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Purchase Order');
        
        $this->authorizeResource(PurchaseOrder::class);

        $this->purchaseOrder = $purchaseOrder;
    }

    public function index(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrders = $purchaseOrder->getAll()->load(['customer', 'createdBy', 'updatedBy']);

        $totalPurchaseOrders = $purchaseOrder->countPurchaseOrdersOfCompany();

        return view('purchase_orders.index', compact('purchaseOrders', 'totalPurchaseOrders'));
    }

    public function create(Product $product, Customer $customer)
    {
        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        return view('purchase_orders.create', compact('products', 'customers'));
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        $purchaseOrder = DB::transaction(function () use ($request) {
            $purchaseOrder = $this->purchaseOrder->create($request->except('purchaseOrder'));

            $purchaseOrder->purchaseOrderDetails()->createMany($request->purchaseOrder);

            return $purchaseOrder;
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder->id);
    }

    public function close(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('create', $purchaseOrder);

        foreach ($purchaseOrder->purchaseOrderDetails as $purchaseOrderDetail) {
            $purchaseOrderDetail->quantity_left = 0;
            $purchaseOrderDetail->save();
        }

        $purchaseOrder->changeStatusToClose();

        return redirect()->back();
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['purchaseOrderDetails.product', 'customer', 'company']);

        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder, Product $product, Customer $customer)
    {
        $purchaseOrder->load(['purchaseOrderDetails.product', 'customer']);

        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        return view('purchase_orders.edit', compact('purchaseOrder', 'products', 'customers'));
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->isPurchaseOrderClosed()) {
            return redirect()->route('purchase-orders.show', $purchaseOrder->id);
        }

        DB::transaction(function () use ($request, $purchaseOrder) {
            $purchaseOrder->update($request->except('purchaseOrder'));

            for ($i = 0; $i < count($request->purchaseOrder); $i++) {
                $purchaseOrder->purchaseOrderDetails[$i]->update($request->purchaseOrder[$i]);
            }
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder->id);

    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
