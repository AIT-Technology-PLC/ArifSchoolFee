<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdjustmentRequest;
use App\Http\Requests\UpdateAdjustmentRequest;
use App\Models\Adjustment;
use App\Models\Warehouse;
use App\Notifications\AdjustmentMade;
use App\Notifications\AdjustmentPrepared;
use App\Services\InventoryOperationService;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    private $permission;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');

        $this->authorizeResource(Adjustment::class, 'adjustment');

        $this->permission = 'Make Adjustment';
    }

    public function index()
    {
        $adjustments = (new Adjustment())->getAll()->load(['createdBy', 'updatedBy', 'approvedBy']);

        $totalAdjustments = $adjustments->count();

        $totalNotApproved = $adjustments->whereNull('approved_by')->count();

        $totalNotAdjusted = $adjustments->whereNotNull('approved_by')->whereNull('adjusted_by')->count();

        $totalAdjusted = $adjustments->whereNotNull('adjusted_by')->count();

        return view('adjustments.index', compact('adjustments', 'totalAdjustments', 'totalNotApproved', 'totalNotAdjusted', 'totalAdjusted'));
    }

    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->assignedWarehouse())->get(['id', 'name']);

        $currentAdjustmentCode = Adjustment::byBranch()->max('code') + 1;

        return view('adjustments.create', compact('warehouses', 'currentAdjustmentCode'));
    }

    public function store(StoreAdjustmentRequest $request)
    {
        $adjustment = DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create($request->except('adjustment'));

            $adjustment->adjustmentDetails()->createMany($request->adjustment);

            Notification::send($this->notifiableUsers('Approve Adjustment'), new AdjustmentPrepared($adjustment));

            return $adjustment;
        });

        return redirect()->route('adjustments.show', $adjustment->id);
    }

    public function show(Adjustment $adjustment)
    {
        $adjustment->load(['adjustmentDetails.warehouse', 'adjustmentDetails.product']);

        return view('adjustments.show', compact('adjustment'));
    }

    public function edit(Adjustment $adjustment)
    {
        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->assignedWarehouse())->get(['id', 'name']);

        return view('adjustments.edit', compact('adjustment', 'warehouses'));
    }

    public function update(UpdateAdjustmentRequest $request, Adjustment $adjustment)
    {
        if ($adjustment->isApproved()) {
            return redirect()->back();
        }

        DB::transaction(function () use ($request, $adjustment) {
            $adjustment->update($request->except('adjustment'));

            for ($i = 0; $i < count($request->adjustment); $i++) {
                $adjustment->adjustmentDetails[$i]->update($request->adjustment[$i]);
            }
        });

        return redirect()->route('adjustments.show', $adjustment->id);
    }

    public function destroy(Adjustment $adjustment)
    {
        if ($adjustment->isAdjusted()) {
            abort(403);
        }

        if ($adjustment->isApproved() && !auth()->user()->can('Delete Approved Adjustment')) {
            abort(403);
        }

        $adjustment->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function adjust(Adjustment $adjustment)
    {
        $this->authorize('adjust', $adjustment);

        if (!$adjustment->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This Adjustment is not approved');
        }

        $result = DB::transaction(function () use ($adjustment) {
            $result = InventoryOperationService::adjust($adjustment->adjustmentDetails);

            if (!$result['isAdjusted']) {
                DB::rollBack();

                return $result;
            }

            $adjustment->adjust();

            Notification::send(
                $this->notifiableUsers('Approve Adjustment', $adjustment->createdBy),
                new AdjustmentMade($adjustment)
            );

            return $result;
        });

        return $result['isAdjusted'] ?
        redirect()->back() :
        redirect()->back()->with('failedMessage', $result['unavailableProducts']);
    }
}
