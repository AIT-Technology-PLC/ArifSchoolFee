<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\WarehouseDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Warehouse Management');

        $this->authorizeResource(Warehouse::class, 'warehouse');
    }

    public function index(WarehouseDatatable $datatable)
    {
        $datatable->builder()->setTableId('warehouses-datatable')->orderBy(1, 'asc');

        $totalWarehouses = Warehouse::count();

        $totalActiveWarehouses = Warehouse::active()->count();

        $totalInActiveWarehouses = $totalWarehouses - $totalActiveWarehouses;

        return $datatable->render('warehouses.index', compact('totalWarehouses', 'totalActiveWarehouses', 'totalInActiveWarehouses'));
    }

    public function create()
    {
        if (limitReached('warehouse', Warehouse::active()->count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));
        }

        return view('warehouses.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        if (limitReached('warehouse', Warehouse::active()->count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));
        }

        Warehouse::firstOrCreate(
            $request->safe()->only(['name'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('warehouses.index');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        if (! $warehouse->isActive() && $request->validated('is_active') && limitReached('warehouse', Warehouse::active()->count())) {
            $warehouse->update($request->safe()->except('is_active'));

            return redirect()->route('warehouses.index')->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));
        }

        $warehouse->update($request->validated());

        return redirect()->route('warehouses.index');
    }
}
