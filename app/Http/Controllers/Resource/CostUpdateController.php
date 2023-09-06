<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CostUpdateDatatable;
use App\DataTables\CostUpdateDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCostUpdateRequest;
use App\Http\Requests\UpdateCostUpdateRequest;
use App\Models\CostUpdate;
use App\Notifications\CostUpdatePrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CostUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Cost Update Management');

        $this->authorizeResource(CostUpdate::class);
    }

    public function index(CostUpdateDatatable $datatable)
    {
        $datatable->builder()->setTableId('cost-updates-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalCostUpdates = CostUpdate::count();

        $totalNotApproved = CostUpdate::notApproved()->notRejected()->count();

        $totalApproved = CostUpdate::approved()->count();

        $totalRejected = CostUpdate::rejected()->count();

        return $datatable->render('cost-updates.index', compact('totalCostUpdates', 'totalNotApproved', 'totalApproved', 'totalRejected'));
    }

    public function create()
    {
        $currentCostUpdateCode = nextReferenceNumber('cost_updates');

        return view('cost-updates.create', compact('currentCostUpdateCode'));
    }

    public function store(StoreCostUpdateRequest $request)
    {
        $costUpdate = DB::transaction(function () use ($request) {
            $costUpdate = CostUpdate::create($request->safe()->except('costUpdate'));

            $costUpdate->costUpdateDetails()->createMany($request->validated('costUpdate'));

            Notification::send(Notifiables::byNextActionPermission('Approve Cost Update'), new CostUpdatePrepared($costUpdate));

            return $costUpdate;
        });

        return redirect()->route('cost-updates.show', $costUpdate->id);
    }

    public function show(CostUpdate $costUpdate, CostUpdateDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('cost-update-details-datatable');

        $costUpdate->load(['costUpdateDetails.product']);

        return $datatable->render('cost-updates.show', compact('costUpdate'));
    }

    public function edit(CostUpdate $costUpdate)
    {
        if ($costUpdate->isRejected()) {
            return redirect()->route('cost-updates.show', $costUpdate->id)
                ->with('failedMessage', 'Rejected cost update cannot be edited.');
        }

        if ($costUpdate->isApproved()) {
            return redirect()->route('cost-updates.show', $costUpdate->id)
                ->with('failedMessage', 'Approved cost update cannot be edited.');
        }

        $costUpdate->load(['costUpdateDetails.product']);

        return view('cost-updates.edit', compact('costUpdate'));
    }

    public function update(UpdateCostUpdateRequest $request, CostUpdate $costUpdate)
    {
        if ($costUpdate->isRejected()) {
            return redirect()->route('cost-updates.show', $costUpdate->id)
                ->with('failedMessage', 'Rejected cost update cannot be edited.');
        }

        if ($costUpdate->isApproved()) {
            return redirect()->route('cost-updates.show', $costUpdate->id)
                ->with('failedMessage', 'Approved cost update cannot be edited.');
        }

        DB::transaction(function () use ($request, $costUpdate) {
            $costUpdate->update($request->safe()->except('costUpdate'));

            $costUpdate->costUpdateDetails()->forceDelete();

            $costUpdate->costUpdateDetails()->createMany($request->validated('costUpdate'));
        });

        return redirect()->route('cost-updates.show', $costUpdate->id);
    }

    public function destroy(CostUpdate $costUpdate)
    {
        abort_if($costUpdate->isApproved(), 403);

        $costUpdate->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
