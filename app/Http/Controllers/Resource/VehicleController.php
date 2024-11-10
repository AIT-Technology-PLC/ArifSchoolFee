<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\VehicleDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Vehicle Management');

        $this->authorizeResource(Vehicle::class, 'vehicle');
    }

    public function index(VehicleDatatable $datatable)
    {
        $datatable->builder()->setTableId('vehicles-datatable')->orderBy(0, 'asc');

        $totalVehicles= Vehicle::count();

        return $datatable->render('vehicles.index', compact('totalVehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(StoreVehicleRequest $request)
    {
        Vehicle::firstOrCreate(
            $request->safe()->only(['vehicle_number'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['vehicle_number'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('vehicles.index')->with('successMessage', 'New Data Created Successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return redirect()->route('vehicles.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->Delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}
