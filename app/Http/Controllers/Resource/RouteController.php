<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\RouteDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use App\Models\Route;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Route Management');

        $this->authorizeResource(Route::class, 'route');
    }

    public function index(RouteDatatable $datatable)
    {
        $datatable->builder()->setTableId('routes-datatable')->orderBy(0, 'asc');

        $totalRoutes= Route::count();

        return $datatable->render('routes.index', compact('totalRoutes'));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('vehicle_number')->get(['id', 'vehicle_number']);

        return view('routes.create',compact('vehicles'));
    }

    public function store(StoreRouteRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('route') as $data) {
                $route = Route::create(Arr::except($data, ['vehicle_id']));

                $route->vehicles()->sync($data['vehicle_id']);
            }
        });

        return redirect()->route('routes.index')->with('successMessage', 'New Route Created Successfully.');
    }

    public function edit(Route $route)
    {
        $vehicles = Vehicle::orderBy('vehicle_number')->get(['id', 'vehicle_number']);

        return view('routes.edit', compact('route','vehicles'));
    }

    public function update(UpdateRouteRequest $request, Route $route)
    {
        DB::transaction(function () use ($request, $route) {
            $route->update($request->safe()->except('vehicle_id'));

            $route->vehicles()->sync($request->validated('vehicle_id'));
        });

        return redirect()->route('routes.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(Route $route)
    {
        if ($route->students()->exists()) {
            return back()->with(['failedMessage' => 'This Route Date is being used and cannot be deleted.']);
        }

        $route->delete();

        return back()->with('deleted', 'Deleted Successfully.');
    }
}
