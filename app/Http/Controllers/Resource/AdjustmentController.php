<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdjustmentRequest;
use App\Http\Requests\UpdateAdjustmentRequest;
use App\Models\Adjustment;
use App\Notifications\AdjustmentPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');

        $this->authorizeResource(Adjustment::class, 'adjustment');
    }

    public function index()
    {
        $adjustments = Adjustment::with(['createdBy', 'updatedBy', 'approvedBy', 'adjustedBy'])->latest()->get();

        $totalAdjustments = Adjustment::count();

        $totalNotApproved = Adjustment::notApproved()->count();

        $totalNotAdjusted = Adjustment::approved()->whereNull('adjusted_by')->count();

        $totalAdjusted = Adjustment::whereNotNull('adjusted_by')->count();

        return view('adjustments.index', compact('adjustments', 'totalAdjustments', 'totalNotApproved', 'totalNotAdjusted', 'totalAdjusted'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('adjustment');

        $currentAdjustmentCode = Adjustment::byBranch()->max('code') + 1;

        return view('adjustments.create', compact('warehouses', 'currentAdjustmentCode'));
    }

    public function store(StoreAdjustmentRequest $request)
    {
        $adjustment = DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create($request->except('adjustment'));

            $adjustment->adjustmentDetails()->createMany($request->adjustment);

            Notification::send(notifiables('Approve Adjustment'), new AdjustmentPrepared($adjustment));

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
        $adjustment->load(['adjustmentDetails.warehouse', 'adjustmentDetails.product']);

        $warehouses = auth()->user()->getAllowedWarehouses('adjustment');

        return view('adjustments.edit', compact('adjustment', 'warehouses'));
    }

    public function update(UpdateAdjustmentRequest $request, Adjustment $adjustment)
    {
        if ($adjustment->isApproved()) {
            return redirect()->route('adjustments.show', $adjustment->id);
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

        return back()->with('deleted', 'Deleted successfully.');
    }
}
