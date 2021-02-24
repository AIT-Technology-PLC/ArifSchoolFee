<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\AddPurchasedItemsToInventory;
use App\Traits\Approvable;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    use PrependCompanyId, Approvable;

    private $grn;

    public function __construct(Grn $grn)
    {
        $this->authorizeResource(Grn::class, 'grn');

        $this->grn = $grn;
    }

    public function index(Grn $grn)
    {
        $grns = $grn->getAll()->load(['createdBy', 'updatedBy', 'approvedBy', 'supplier', 'purchase']);

        $totalGrns = $grn->countGrnsOfCompany();

        return view('grns.index', compact('grns', 'totalGrns'));
    }

    public function create(Product $product, Warehouse $warehouse, Supplier $supplier, Purchase $purchase)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $suppliers = $supplier->getSupplierNames();

        $purchases = $purchase->getManualPurchases();

        $currentGrnCode = (Grn::select('code')->companyGrn()->latest()->first()->code) ?? 0;

        return view('grns.create', compact('products', 'warehouses', 'suppliers', 'purchases', 'currentGrnCode'));
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
            'grn.*.description' => 'nullable|string',
            'supplier_id' => 'nullable|integer',
            'purchase_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $grnData['status'] = 'Not Added To Inventory';
        $grnData['company_id'] = auth()->user()->employee->company_id;
        $grnData['created_by'] = auth()->user()->id;
        $grnData['updated_by'] = auth()->user()->id;
        $grnData['approved_by'] = $this->approvedBy();

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
        $grn->load(['grnDetails.product', 'grnDetails.warehouse', 'supplier', 'purchase']);

        return view('grns.show', compact('grn'));
    }

    public function edit(Grn $grn, Product $product, Warehouse $warehouse, Supplier $supplier, Purchase $purchase)
    {
        $grn->load(['grnDetails.product', 'grnDetails.warehouse', 'supplier', 'purchase']);

        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $suppliers = $supplier->getSupplierNames();

        $purchases = $purchase->getManualPurchases();

        return view('grns.edit', compact('grn', 'products', 'warehouses', 'suppliers', 'purchases'));
    }

    public function update(Request $request, Grn $grn)
    {
        if ($grn->isAddedToInventory()) {
            $grnPurchaseId = $request->validate([
                'purchase_id' => 'nullable|integer',
            ]);

            $grnPurchaseId['updated_by'] = auth()->user()->id;

            $grn->update($grnPurchaseId);

            return redirect()->route('grns.show', $grn->id);
        }

        $request['code'] = $this->prependCompanyId($request->code);

        $grnData = $request->validate([
            'code' => 'required|string|unique:grns,code,' . $grn->id,
            'grn' => 'required|array',
            'grn.*.product_id' => 'required|integer',
            'grn.*.warehouse_id' => 'required|integer',
            'grn.*.quantity' => 'required|numeric|min:1',
            'grn.*.description' => 'nullable|string',
            'supplier_id' => 'nullable|integer',
            'purchase_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $grnData['updated_by'] = auth()->user()->id;

        $basicGrnData = Arr::except($grnData, 'grn');
        $grnDetailsData = $grnData['grn'];

        DB::transaction(function () use ($basicGrnData, $grnDetailsData, $grn) {
            $grn->update($basicGrnData);

            for ($i = 0; $i < count($grnDetailsData); $i++) {
                $grn->grnDetails[$i]->update($grnDetailsData[$i]);
            }
        });

        return redirect()->route('grns.show', $grn->id);
    }

    public function destroy(Grn $grn)
    {
        $grn->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
