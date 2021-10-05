<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDamageRequest;
use App\Http\Requests\UpdateDamageRequest;
use App\Models\Damage;
use App\Models\Warehouse;
use App\Notifications\DamagePrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DamageController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Damage Management');

        $this->authorizeResource(Damage::class, 'damage');
    }

    public function index()
    {
        $damages = (new Damage)->getAll()->load(['damageDetails', 'createdBy', 'updatedBy', 'approvedBy']);

        $totalDamages = Damage::count();

        $totalNotApproved = Damage::whereNull('approved_by')->count();

        $totalNotSubtracted = Damage::whereNull('subtracted_by')->whereNotNull('approved_by')->count();

        $totalSubtracted = Damage::whereNotNull('subtracted_by')->count();

        return view('damages.index', compact('damages', 'totalDamages', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create()
    {
        $warehouses = Warehouse::orderBy('name')->whereIn('id', user()->getAllowedWarehouses('subtract'))->get(['id', 'name']);

        $currentDamageCode = Damage::byBranch()->max('code') + 1;

        return view('damages.create', compact('warehouses', 'currentDamageCode'));
    }

    public function store(StoreDamageRequest $request)
    {
        $damage = DB::transaction(function () use ($request) {
            $damage = Damage::create($request->except('damage'));

            $damage->damageDetails()->createMany($request->damage);

            Notification::send(notifiables('Approve Damage'), new DamagePrepared($damage));

            return $damage;
        });

        return redirect()->route('damages.show', $damage->id);
    }

    public function show(Damage $damage)
    {
        $damage->load(['damageDetails.product', 'damageDetails.warehouse']);

        return view('damages.show', compact('damage'));
    }

    public function edit(Damage $damage)
    {
        $warehouses = Warehouse::orderBy('name')->whereIn('id', user()->getAllowedWarehouses('subtract'))->get(['id', 'name']);

        $damage->load(['damageDetails.product', 'damageDetails.warehouse']);

        return view('damages.edit', compact('damage', 'warehouses'));
    }

    public function update(UpdateDamageRequest $request, Damage $damage)
    {
        DB::transaction(function () use ($request, $damage) {
            $damage->update($request->except('damage'));

            for ($i = 0; $i < count($request->damage); $i++) {
                $damage->damageDetails[$i]->update($request->damage[$i]);
            }
        });

        return redirect()->route('damages.show', $damage->id);
    }

    public function destroy(Damage $damage)
    {
        if ($damage->isSubtracted()) {
            abort(403);
        }

        if ($damage->isApproved() && !auth()->user()->can('Delete Approved Damage')) {
            abort(403);
        }

        $damage->forceDelete();

        return back()->with('deleted', 'Deleted Successfully');
    }
}
