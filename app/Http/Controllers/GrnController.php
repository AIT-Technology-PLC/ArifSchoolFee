<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGrnRequest;
use App\Http\Requests\UpdateGrnRequest;
use App\Models\Grn;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Notifications\GrnPrepared;
use App\Traits\InventoryActions;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GrnController extends Controller
{
    use NotifiableUsers, InventoryActions;

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

        $purchases = $purchase->getAll();

        $currentGrnCode = (Grn::select('code')->companyGrn()->latest()->first()->code) ?? 0;

        return view('grns.create', compact('products', 'warehouses', 'suppliers', 'purchases', 'currentGrnCode'));
    }

    public function store(StoreGrnRequest $request)
    {
        $grn = DB::transaction(function () use ($request) {
            $grn = $this->grn->create($request->except('grn'));

            $grn->grnDetails()->createMany($request->grn);

            Notification::send($this->notifiableUsers('Approve GRN'), new GrnPrepared($grn));

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

        $purchases = $purchase->getAll();

        return view('grns.edit', compact('grn', 'products', 'warehouses', 'suppliers', 'purchases'));
    }

    public function update(UpdateGrnRequest $request, Grn $grn)
    {
        if ($grn->isApproved()) {
            $grn->update($request->only(['purchase_id', 'updated_by']));

            return redirect()->route('grns.show', $grn->id);
        }

        DB::transaction(function () use ($request, $grn) {
            $grn->update($request->except('grn'));

            for ($i = 0; $i < count($request->grn); $i++) {
                $grn->grnDetails[$i]->update($request->grn[$i]);
            }
        });

        return redirect()->route('grns.show', $grn->id);
    }

    public function destroy(Grn $grn)
    {
        if ($grn->isApproved() && !auth()->user()->can('Delete Approved GRN')) {
            return view('errors.permission_denied');
        }

        $grn->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
