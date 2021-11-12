<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDamageRequest;
use App\Http\Requests\UpdateDamageRequest;
use App\Models\Damage;
use App\Notifications\DamagePrepared;
use App\Services\NextReferenceNumService;
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
        $damages = Damage::with(['damageDetails', 'createdBy', 'updatedBy', 'approvedBy'])->latest('code')->get();

        $totalDamages = Damage::count();

        $totalNotApproved = Damage::notApproved()->count();

        $totalNotSubtracted = Damage::notSubtracted()->approved()->count();

        $totalSubtracted = Damage::subtracted()->count();

        return view('damages.index', compact('damages', 'totalDamages', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('subtract');

        $currentDamageCode = NextReferenceNumService::table('damages');

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
        $warehouses = auth()->user()->getAllowedWarehouses('subtract');

        $damage->load(['damageDetails.product', 'damageDetails.warehouse']);

        return view('damages.edit', compact('damage', 'warehouses'));
    }

    public function update(UpdateDamageRequest $request, Damage $damage)
    {
        if ($damage->isApproved()) {
            return redirect()->route('damages.show', $damage->id)
                ->with('failedMessage', 'Approved damages cannot be edited.');
        }

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
        abort_if($damage->isSubtracted(), 403);

        abort_if($damage->isApproved() && !auth()->user()->can('Delete Approved Damage'), 403);

        $damage->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
