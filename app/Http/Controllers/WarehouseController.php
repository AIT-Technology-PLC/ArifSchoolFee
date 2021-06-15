<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    private $warehouse;

    public function __construct(Warehouse $warehouse)
    {
        $this->authorizeResource(Warehouse::class, 'warehouse');

        $this->warehouse = $warehouse;

        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Warehouse Management');
    }

    public function index()
    {
        $warehouses = $this->warehouse->getAll();

        $totalWarehousesOfCompany = $this->warehouse->countWarehousesOfCompany();

        return view('warehouses.index', compact('warehouses', 'totalWarehousesOfCompany'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(StoreWarehouseRequest $request)
    {
        if (limitReached('warehouse', Warehouse::companyWarehouses()->count())) {
            return redirect()->back()
                ->with('limitReachedMessage', 'You have reached the allowed number of warehouses in respect to your package.');
        }

        $this->warehouse->firstOrCreate(
            $request->only(['name', 'company_id']),
            $request->except(['name', 'company_id']),
        );

        return redirect()->route('warehouses.index');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->all());

        return redirect()->route('warehouses.index');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
