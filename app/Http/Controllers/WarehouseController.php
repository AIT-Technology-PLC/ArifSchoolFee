<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private $warehouse;

    public function __construct(Warehouse $warehouse)
    {
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

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->employee->company_id;

        $this->warehouse->create($data);

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

        $data['updated_by'] = auth()->user()->id;

        $warehouse->update($data);

        return redirect()->route('warehouses.index');
    }

    public function destroy(Warehouse $warehouse)
    {
        //
    }
}
