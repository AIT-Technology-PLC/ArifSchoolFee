<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AdjustmentDatatable;
use App\DataTables\AdjustmentDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdjustmentRequest;
use App\Http\Requests\UpdateAdjustmentRequest;
use App\Models\Adjustment;
use App\Notifications\AdjustmentPrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');

        $this->authorizeResource(Adjustment::class, 'adjustment');
    }

    public function index(AdjustmentDatatable $datatable)
    {
        $datatable->builder()->setTableId('adjustments-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalAdjustments = Adjustment::count();

        $totalNotApproved = Adjustment::notApproved()->count();

        $totalNotAdjusted = Adjustment::approved()->notAdjusted()->count();

        $totalAdjusted = Adjustment::adjusted()->count();

        return $datatable->render('adjustments.index', compact('totalAdjustments', 'totalNotApproved', 'totalNotAdjusted', 'totalAdjusted'));
    }

    public function create()
    {
        $warehouses = authUser()->getAllowedWarehouses('adjustment');

        $currentAdjustmentCode = nextReferenceNumber('adjustments');

        return view('adjustments.create', compact('warehouses', 'currentAdjustmentCode'));
    }

    public function store(StoreAdjustmentRequest $request)
    {
        $adjustment = DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create($request->safe()->except('adjustment'));

            $adjustment->adjustmentDetails()->createMany($request->validated('adjustment'));

            Notification::send(Notifiables::byNextActionPermission('Approve Adjustment'), new AdjustmentPrepared($adjustment));

            return $adjustment;
        });

        return redirect()->route('adjustments.show', $adjustment->id);
    }

    public function show(Adjustment $adjustment, AdjustmentDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('adjustment-details-datatable');

        $adjustment->load(['adjustmentDetails.warehouse', 'adjustmentDetails.product', 'adjustmentDetails.merchandiseBatch']);

        return $datatable->render('adjustments.show', compact('adjustment'));
    }

    public function edit(Adjustment $adjustment)
    {
        $adjustment->load(['adjustmentDetails.warehouse', 'adjustmentDetails.product', 'adjustmentDetails.merchandiseBatch']);

        $warehouses = authUser()->getAllowedWarehouses('adjustment');

        return view('adjustments.edit', compact('adjustment', 'warehouses'));
    }

    public function update(UpdateAdjustmentRequest $request, Adjustment $adjustment)
    {
        if ($adjustment->isAdjusted()) {
            return redirect()->route('adjustments.show', $adjustment->id)
                ->with('failedMessage', 'Executed adjustments cannot be edited.');
        }

        DB::transaction(function () use ($request, $adjustment) {
            $adjustment->update($request->safe()->except('adjustment'));

            $adjustment->adjustmentDetails()->forceDelete();

            $adjustment->adjustmentDetails()->createMany($request->validated('adjustment'));
        });

        return redirect()->route('adjustments.show', $adjustment->id);
    }

    public function destroy(Adjustment $adjustment)
    {
        abort_if($adjustment->isAdjusted(), 403);

        abort_if($adjustment->isApproved() && !authUser()->can('Delete Approved Adjustment'), 403);

        $adjustment->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
