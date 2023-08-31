<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\GrnDatatable;
use App\DataTables\GrnDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGrnRequest;
use App\Http\Requests\UpdateGrnRequest;
use App\Models\Grn;
use App\Models\Supplier;
use App\Notifications\GrnPrepared;
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
        $warehouses = authUser()->getAllowedWarehouses('add');

        $suppliers = Supplier::validBusinessLicense()->orderBy('company_name')->get(['id', 'company_name']);

        $currentGrnCode = nextReferenceNumber('grns');

        return view('grns.create', compact('warehouses', 'suppliers', 'currentGrnCode'));
    }

    public function store(StoreGrnRequest $request)
    {
        $grn = DB::transaction(function () use ($request) {
            $grn = Grn::create($request->safe()->except('grn'));

            $grn->grnDetails()->createMany($request->validated('grn'));

            $grn->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve GRN'), new GrnPrepared($grn));

            return $grn;
        });

        return redirect()->route('grns.show', $grn->id);
    }

    public function show(Grn $grn, GrnDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('grn-details-datatable');

        $grn->load(['grnDetails.product', 'grnDetails.warehouse', 'supplier', 'purchase', 'customFieldValues.customField']);

        return $datatable->render('grns.show', compact('grn'));
    }

    public function edit(Grn $grn)
    {
        $grn->load(['grnDetails.product', 'grnDetails.warehouse', 'supplier', 'purchase']);

        $warehouses = authUser()->getAllowedWarehouses('add');

        $suppliers = Supplier::validBusinessLicense()->orderBy('company_name')->get(['id', 'company_name']);

        return view('grns.edit', compact('grn', 'warehouses', 'suppliers'));
    }

    public function update(UpdateGrnRequest $request, Grn $grn)
    {
        if ($grn->isApproved()) {
            $grn->update($request->safe()->only(['purchase_id', 'description']));

            return redirect()->route('grns.show', $grn->id);
        }

        DB::transaction(function () use ($request, $grn) {
            $grn->update($request->safe()->except('grn'));

            $grn->grnDetails()->forceDelete();

            $grn->grnDetails()->createMany($request->validated('grn'));

            $grn->createCustomFields($request->validated('customField'));
        });

        return redirect()->route('grns.show', $grn->id);
    }

    public function destroy(Grn $grn)
    {
        abort_if($grn->isAdded(), 403);

        abort_if($grn->isApproved() && !authUser()->can('Delete Approved GRN'), 403);

        $grn->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
