<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\GrnDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrnRequest;
use App\Http\Requests\UpdateGrnRequest;
use App\Models\Grn;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Notifications\GrnPrepared;
use App\Services\NextReferenceNumService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GrnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Grn Management');

        $this->authorizeResource(Grn::class, 'grn');
    }

    public function index(GrnDatatable $datatable)
    {
        $datatable->builder()->setTableId('grns-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalAdded = Grn::added()->count();

        $totalNotApproved = Grn::notApproved()->count();

        $totalNotAdded = Grn::approved()->notAdded()->count();

        $totalGrns = Grn::count();

        return $datatable->render('grns.index', compact('totalGrns', 'totalAdded', 'totalNotApproved', 'totalNotAdded'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('add');

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $purchases = Purchase::latest('code')->get();

        $currentGrnCode = NextReferenceNumService::table('grns');

        return view('grns.create', compact('warehouses', 'suppliers', 'purchases', 'currentGrnCode'));
    }

    public function store(StoreGrnRequest $request)
    {
        $grn = DB::transaction(function () use ($request) {
            $grn = Grn::create($request->except('grn'));

            $grn->grnDetails()->createMany($request->grn);

            Notification::send(Notifiables::nextAction('Approve GRN'), new GrnPrepared($grn));

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

        $warehouses = auth()->user()->getAllowedWarehouses('add');

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $purchases = Purchase::latest('code')->get();

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
        abort_if($grn->isAdded(), 403);

        abort_if($grn->isApproved() && !auth()->user()->can('Delete Approved GRN'), 403);

        $grn->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
