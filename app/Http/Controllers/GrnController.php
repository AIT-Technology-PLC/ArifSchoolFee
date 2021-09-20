<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGrnRequest;
use App\Http\Requests\UpdateGrnRequest;
use App\Models\Grn;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Notifications\GrnPrepared;
use App\Traits\AddInventory;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GrnController extends Controller
{
    use NotifiableUsers, AddInventory, ApproveInventory;

    private $grn;

    private $permission;

    public function __construct(Grn $grn)
    {
        $this->middleware('isFeatureAccessible:Grn Management');

        $this->authorizeResource(Grn::class, 'grn');

        $this->grn = $grn;

        $this->permission = 'Add GRN';
    }

    public function index(Grn $grn)
    {
        $grns = $grn->getAll()->load(['createdBy', 'updatedBy', 'approvedBy', 'supplier', 'purchase']);

        $totalAdded = $grns->where('status', 'Added To Inventory')->count();

        $totalNotApproved = $grns->whereNull('approved_by')->count();

        $totalNotAdded = $grns->whereNotNull('approved_by')->where('status', 'Not Added To Inventory')->count();

        $totalGrns = $grns->count();

        return view('grns.index', compact('grns', 'totalGrns', 'totalAdded', 'totalNotApproved', 'totalNotAdded'));
    }

    public function create(Warehouse $warehouse, Supplier $supplier, Purchase $purchase)
    {
        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->addWarehouses());

        $suppliers = $supplier->getSupplierNames();

        $purchases = $purchase->getAll();

        $currentGrnCode = (Grn::select('code')->companyGrn()->latest()->first()->code) ?? 0;

        return view('grns.create', compact('warehouses', 'suppliers', 'purchases', 'currentGrnCode'));
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

    public function edit(Grn $grn, Warehouse $warehouse, Supplier $supplier, Purchase $purchase)
    {
        $grn->load(['grnDetails.product', 'grnDetails.warehouse', 'supplier', 'purchase']);

        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->addWarehouses());

        $suppliers = $supplier->getSupplierNames();

        $purchases = $purchase->getAll();

        return view('grns.edit', compact('grn', 'warehouses', 'suppliers', 'purchases'));
    }

    public function update(UpdateGrnRequest $request, Grn $grn)
    {
        if ($grn->isApproved()) {
            $grn->update($request->only(['purchase_id', 'description', 'updated_by']));

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
        if ($grn->isAdded()) {
            abort(403);
        }

        if ($grn->isApproved() && !auth()->user()->can('Delete Approved GRN')) {
            abort(403);
        }

        $grn->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
