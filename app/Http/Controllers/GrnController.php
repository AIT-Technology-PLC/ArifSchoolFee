<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\AddPurchasedItemsToInventory;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    use PrependCompanyId;

    private $grn;

    public function __construct(Grn $grn)
    {
        $this->grn = $grn;
    }

    public function index()
    {
        //
    }

    public function create(Product $product, Warehouse $warehouse, Supplier $supplier, Purchase $purchase)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $suppliers = $supplier->getSupplierNames();

        $purchases = $purchase->getManualPurchases();

        return view('grns.create', compact('products', 'warehouses', 'suppliers', 'purchases'));
    }

    public function store(Request $request)
    {
        $request['code'] = $this->prependCompanyId($request->code);

        $grnData = $request->validate([
            'code' => 'required|string|unique:grns',
            'grn' => 'required|array',
            'grn.*.product_id' => 'required|integer',
            'grn.*.warehouse_id' => 'required|integer',
            'grn.*.quantity' => 'required|numeric|min:1',
            'grn.*.desciption' => 'nullable|string',
            'supplier_id' => 'nullable|integer',
            'purchase_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $grnData['company_id'] = auth()->user()->employee->company_id;
        $grnData['created_by'] = auth()->user()->id;
        $grnData['updated_by'] = auth()->user()->id;

        $basicGrnData = Arr::except($grnData, 'grn');
        $grnDetailsData = $grnData['grn'];

        $grn = DB::transaction(function () use ($basicGrnData, $grnDetailsData) {
            $grn = $this->grn->create($basicGrnData);
            $grn->grnDetails()->createMany($grnDetailsData);
            AddPurchasedItemsToInventory::addToInventory($grn);

            return $grn;
        });

        return redirect()->route('grns.show', $grn->id);
    }

    public function show(Grn $grn)
    {
        //
    }

    public function edit(Grn $grn)
    {
        //
    }

    public function update(Request $request, Grn $grn)
    {
        //
    }

    public function destroy(Grn $grn)
    {
        //
    }
}
