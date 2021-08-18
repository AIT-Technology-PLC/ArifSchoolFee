<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageRequest;
use App\Http\Requests\UpdateDamageRequest;
use App\Models\Damage;
use App\Models\Warehouse;
use App\Notifications\DamagePrepared;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DamageController extends Controller
{
    use NotifiableUsers, SubtractInventory;

    public function __construct()
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Damage Management');

        $this->authorizeResource(Damage::class, 'damage');

        $this->permission = 'Subtract Damage';
    }

    public function index()
    {
        $damages = Damage::companyDamage()
            ->with(['damageDetails', 'createdBy', 'updatedBy', 'approvedBy', 'company'])
            ->latest()->get();

        $totalDamages = $damages->count();

        $totalNotApproved = $damages->whereNull('approved_by')->count();

        $totalNotSubtracted = $damages->where('status', 'Not Subtracted From Inventory')->whereNotNull('approved_by')->count();

        $totalSubtracted = $damages->where('status', 'Subtracted From Inventory')->count();

        return view('damages.index', compact('damages', 'totalDamages', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create(Warehouse $warehouse)
    {
        $warehouses = $warehouse->getAllWithoutRelations();

        $currentDamageCode = (Damage::select('code')->companyDamage()->latest()->first()->code) ?? 0;

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
        $warehouses = $warehouse->getAllWithoutRelations();

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
            return view('errors.permission_denied');
        }

        if ($damage->isApproved() && !auth()->user()->can('Delete Approved Damage')) {
            return view('errors.permission_denied');
        }

        $damage->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
