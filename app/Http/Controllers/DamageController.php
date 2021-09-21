<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageRequest;
use App\Http\Requests\UpdateDamageRequest;
use App\Models\Damage;
use App\Models\Warehouse;
use App\Notifications\DamagePrepared;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DamageController extends Controller
{
    use NotifiableUsers, SubtractInventory, ApproveInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Damage Management');

        $this->authorizeResource(Damage::class, 'damage');

        $this->permission = 'Subtract Damage';
    }

    public function index()
    {
        $damages = (new Damage())->getAll()->load(['damageDetails', 'createdBy', 'updatedBy', 'approvedBy', 'company']);

        $totalDamages = $damages->count();

        $totalNotApproved = $damages->whereNull('approved_by')->count();

        $totalNotSubtracted = $damages->whereNull('subtracted_by')->whereNotNull('approved_by')->count();

        $totalSubtracted = $damages->whereNotNull('subtracted_by')->count();

        return view('damages.index', compact('damages', 'totalDamages', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create(Warehouse $warehouse)
    {
        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->assignedWarehouse());

        $currentDamageCode = (Damage::select('code')->latest()->first()->code) ?? 0;

        return view('damages.create', compact('warehouses', 'currentDamageCode'));
    }

    public function store(StoreDamageRequest $request)
    {
        $damage = DB::transaction(function () use ($request) {
            $damage = Damage::create($request->except('damage'));

            $damage->damageDetails()->createMany($request->damage);

            Notification::send($this->notifiableUsers('Approve Damage'), new DamagePrepared($damage));

            return $damage;
        });

        return redirect()->route('damages.show', $damage->id);
    }

    public function show(Damage $damage)
    {
        $damage->load(['damageDetails.product', 'damageDetails.warehouse']);

        return view('damages.show', compact('damage'));
    }

    public function edit(Damage $damage, Warehouse $warehouse)
    {
        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->assignedWarehouse());

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

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
