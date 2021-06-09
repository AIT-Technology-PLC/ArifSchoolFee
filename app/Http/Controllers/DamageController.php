<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageRequest;
use App\Http\Requests\UpdateDamageRequest;
use App\Models\Damage;
use App\Models\Product;
use App\Models\Warehouse;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
{
    use SubtractInventory;

    public function index()
    {
        $damages = Damage::companyDamage()->load(['damageDetails', 'createdBy', 'updatedBy', 'approvedBy', 'company']);

        $totalDamages = $damages->count();

        $totalNotApproved = $damages->whereNull('approved_by')->count();

        $totalNotSubtracted = $damages->where('status', 'Not Subtracted From Inventory')->whereNotNull('approved_by')->count();

        return view('damages.index', compact('damages', 'totalDamages', 'totalNotApproved', 'totalNotSubtracted'));
    }

    public function create(Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentDamageCode = (Damage::select('code')->companyDamage()->latest()->first()->code) ?? 0;

        return view('damages.create', compact('products', 'warehouses', 'currentDamageCode'));
    }

    public function store(StoreDamageRequest $request)
    {
        $damage = DB::transaction(function () use ($request) {
            $damage = $this->damage->create($request->except('damage'));

            $damage->damageDetails()->createMany($request->damage);

            return $damage;
        });

        return redirect()->route('damages.show', $damage->id);
    }

    public function show(Damage $damage)
    {
        $damage->load(['damageDetails.product', 'damageDetails.warehouse', 'company']);

        return view('damages.show', compact('damage'));
    }

    public function edit(Damage $damage, Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $damage->load(['damageDetails.product', 'damageDetails.warehouse']);

        return view('damages.edit', compact('damage', 'products', 'warehouses'));
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
