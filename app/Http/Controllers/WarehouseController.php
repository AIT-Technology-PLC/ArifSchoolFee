<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WarehouseController extends Controller
{
    private $warehouse;

    public function __construct(Warehouse $warehouse)
    {
        $this->authorizeResource(Warehouse::class, 'warehouse');

        $this->warehouse = $warehouse;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['company_id'] = userCompany()->id;
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $this->warehouse->firstOrCreate(
            Arr::only($data, ['name', 'company_id']),
            Arr::except($data, ['name', 'company_id'])
        );

        return redirect()->route('warehouses.index');
    }

    public function show(Warehouse $warehouse)
    {
        //
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['updated_by'] = auth()->id();

        $warehouse->update($data);

        return redirect()->route('warehouses.index');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
