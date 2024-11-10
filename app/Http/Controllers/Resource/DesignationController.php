<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\DesignationDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use App\Models\Designation;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Designation Management');

        $this->authorizeResource(Designation::class);
    }
    
    public function index(DesignationDatatable $datatable)
    {
        $datatable->builder()->setTableId('designations-datatable')->orderBy(1, 'asc');

        $totalDesignations = Designation::count();

        return $datatable->render('designations.index', compact('totalDesignations'));
    }

    public function create()
    {
        return view('designations.create');
    }

    public function store(StoreDesignationRequest $request)
    {
        $designations = collect($request->validated('designation'));

        DB::transaction(function () use ($designations) {
            foreach ($designations as $designation) {
                Designation::firstOrCreate($designation);
            }
        });

        return redirect()->route('designations.index')->with('successMessage', 'New designation are added.');
    }

    public function edit(Designation $designation)
    {
        return view('designations.edit', compact('designation'));
    }

    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        $designation->update($request->validated());

        return redirect()->route('designations.index');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}