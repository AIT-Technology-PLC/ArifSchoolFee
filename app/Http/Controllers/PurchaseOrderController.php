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
        $request['code'] = $this->prependCompanyId($request->code);

        $purchaseOrderData = $request->validate([
            'code' => 'required|string|unique:purchase_orders',
            'purchaseOrder' => 'required|array',
            'purchaseOrder.*.product_id' => 'required|integer',
            'purchaseOrder.*.quantity' => 'required|numeric|min:1',
            'purchaseOrder.*.unit_price' => 'required|numeric',
            'purchaseOrder.*.desciption' => 'nullable|string',
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
