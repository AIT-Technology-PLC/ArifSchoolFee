<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    use PrependCompanyId;

    private $purchaseOrder;

    public function __construct(PurchaseOrder $purchaseOrder)
    {
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

    public function store(Request $request)
    {
        $request['code'] = $this->prependCompanyId($request->code);

        $purchaseOrderData = $request->validate([
            'code' => 'required|string|unique:purchase_orders',
            'purchaseOrder' => 'required|array',
            'purchaseOrder.*.product_id' => 'required|integer',
            'purchaseOrder.*.quantity' => 'required|numeric|min:1',
            'purchaseOrder.*.unit_price' => 'required|numeric',
            'purchaseOrder.*.description' => 'nullable|string',
            'customer_id' => 'nullable|integer',
            'received_on' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $purchaseOrderData['is_closed'] = 0;
        $purchaseOrderData['company_id'] = auth()->user()->employee->company_id;
        $purchaseOrderData['created_by'] = auth()->user()->id;
        $purchaseOrderData['updated_by'] = auth()->user()->id;

        $basicPurchaseOrderData = Arr::except($purchaseOrderData, 'purchaseOrder');
        $purchaseOrderDetailsData = $purchaseOrderData['purchaseOrder'];

        for ($i = 0; $i < count($purchaseOrderDetailsData); $i++) {
            $purchaseOrderDetailsData[$i]['quantity_left'] = $purchaseOrderDetailsData[$i]['quantity'];
        }

        $purchaseOrder = DB::transaction(function () use ($basicPurchaseOrderData, $purchaseOrderDetailsData) {
            $purchaseOrder = $this->purchaseOrder->create($basicPurchaseOrderData);
            $purchaseOrder->purchaseOrderDetails()->createMany($purchaseOrderDetailsData);

            return $purchaseOrder;
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder->id);
    }

    public function close(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('create', $purchaseOrder);

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

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->isPurchaseOrderClosed()) {
            return redirect()->route('purchase-orders.show', $purchaseOrder->id);
        }

        $request['code'] = $this->prependCompanyId($request->code);

        $purchaseOrderData = $request->validate([
            'code' => 'required|string|unique:purchase_orders,code,' . $purchaseOrder->id,
            'purchaseOrder' => 'required|array',
            'purchaseOrder.*.product_id' => 'required|integer',
            'purchaseOrder.*.quantity' => 'required|numeric|min:1',
            'purchaseOrder.*.quantity_left' => 'required|numeric|lte:purchaseOrder.*.quantity',
            'purchaseOrder.*.unit_price' => 'required|numeric',
            'purchaseOrder.*.description' => 'nullable|string',
            'customer_id' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        $purchaseOrderData['updated_by'] = auth()->user()->id;

        $basicPurchaseOrderData = Arr::except($purchaseOrderData, 'purchaseOrder');
        $purchaseOrderDetailsData = $purchaseOrderData['purchaseOrder'];

        DB::transaction(function () use ($basicPurchaseOrderData, $purchaseOrderDetailsData, $purchaseOrder) {
            $purchaseOrder->update($basicPurchaseOrderData);

            for ($i = 0; $i < count($purchaseOrderDetailsData); $i++) {
                $purchaseOrder->purchaseOrderDetails[$i]->update($purchaseOrderDetailsData[$i]);
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
