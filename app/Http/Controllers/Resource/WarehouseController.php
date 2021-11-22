<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Warehouse;
use App\Scopes\ActiveWarehouseScope;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Warehouse Management');

        $this->authorizeResource(Warehouse::class, 'warehouse');
    }

    public function index()
    {
        $warehouses = Warehouse::query()
            ->withoutGlobalScopes([ActiveWarehouseScope::class])
            ->with(['createdBy', 'updatedBy'])->orderBy('name')->get();

        $totalWarehouses = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->count();

        $totalActiveWarehouses = Warehouse::count();

        $totalInActiveWarehouses = $totalWarehouses - $totalActiveWarehouses;

        return view('warehouses.index', compact('warehouses', 'totalWarehouses', 'totalActiveWarehouses', 'totalInActiveWarehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        if (limitReached('warehouse', Warehouse::count())) {
            return back()
                ->with('limitReachedMessage', 'You have reached the allowed number of warehouses in respect to your package.');
        }

        Warehouse::firstOrCreate(
            $request->only(['name'] + ['company_id' => userCompany()->id]),
            $request->except(['name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('warehouses.index');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated());

        return redirect()->route('warehouses.index');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
