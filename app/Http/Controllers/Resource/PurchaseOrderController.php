<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\Customer;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Purchase Order');

        $this->authorizeResource(PurchaseOrder::class);
    }

    public function index()
    {
        $purchaseOrders = (new PurchaseOrder)->getAll()->load(['customer', 'createdBy', 'updatedBy']);

        $totalPurchaseOrders = PurchaseOrder::count();

        $totalClosed = PurchaseOrder::where('is_closed', 1)->count();

        $totalOpen = PurchaseOrder::where('is_closed', 0)->count();

        return view('purchase-orders.index', compact('purchaseOrders', 'totalPurchaseOrders', 'totalClosed', 'totalOpen'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        return view('purchase-orders.create', compact('customers'));
    }

    public function store(StorePurchaseOrderRequest $request)
    {
        $purchaseOrder = DB::transaction(function () use ($request) {
            $purchaseOrder = PurchaseOrder::create($request->except('purchaseOrder'));

            $purchaseOrder->purchaseOrderDetails()->createMany($request->purchaseOrder);

            return $purchaseOrder;
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder->id);
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['purchaseOrderDetails.product', 'customer']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['purchaseOrderDetails.product', 'customer']);

        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        return view('purchase-orders.edit', compact('purchaseOrder', 'customers'));
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->isClosed()) {
            return back();
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
        if ($purchaseOrder->isClosed()) {
            abort(403);
        }

        $purchaseOrder->forceDelete();

        return back()->with('deleted', 'Deleted Successfully');
    }
}
