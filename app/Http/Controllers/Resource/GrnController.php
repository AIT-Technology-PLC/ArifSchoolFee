<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrnRequest;
use App\Http\Requests\UpdateGrnRequest;
use App\Models\Grn;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Notifications\GrnPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GrnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Grn Management');

        $this->authorizeResource(Grn::class, 'grn');
    }

    public function index()
    {
        $grns = (new Grn)->getAll()->load(['createdBy', 'updatedBy', 'approvedBy', 'supplier', 'purchase']);

        $totalAdded = Grn::whereNotNull('added_by')->count();

        $totalNotApproved = Grn::whereNull('approved_by')->count();

        $totalNotAdded = Grn::whereNotNull('approved_by')->whereNull('added_by')->count();

        $totalGrns = Grn::count();

        return view('grns.index', compact('grns', 'totalGrns', 'totalAdded', 'totalNotApproved', 'totalNotAdded'));
    }

    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->addWarehouses())->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $purchases = (new Purchase)->getAll();

        $currentGrnCode = Grn::byBranch()->max('code') + 1;

        return view('grns.create', compact('warehouses', 'suppliers', 'purchases', 'currentGrnCode'));
    }

    public function store(StoreGrnRequest $request)
    {
        $grn = DB::transaction(function () use ($request) {
            $grn = Grn::create($request->except('grn'));

            $grn->grnDetails()->createMany($request->grn);

            Notification::send(notifiables('Approve GRN'), new GrnPrepared($grn));

            return $grn;
        });

        return redirect()->route('grns.show', $grn->id);
    }

    public function show(Grn $grn)
    {
        $grn->load(['grnDetails.product', 'grnDetails.warehouse', 'supplier', 'purchase']);

        return view('grns.show', compact('grn'));
    }

    public function edit(Grn $grn)
    {
        $grn->load(['grnDetails.product', 'grnDetails.warehouse', 'supplier', 'purchase']);

        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->addWarehouses())->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $purchases = (new Purchase)->getAll();

        return view('grns.edit', compact('grn', 'warehouses', 'suppliers', 'purchases'));
    }

    public function update(UpdateGrnRequest $request, Grn $grn)
    {
        if ($grn->isApproved()) {
            $grn->update($request->only(['purchase_id', 'description']));

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

        return back()->with('deleted', 'Deleted Successfully');
    }
}
