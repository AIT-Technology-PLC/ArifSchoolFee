<?php

namespace App\Http\Controllers\Resource;

use App\Actions\CreateStaffDetail;
use App\Actions\UpdateStaffDetail;
use App\DataTables\StaffDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Staff;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Staff Management');

        $this->authorizeResource(Staff::class, 'staff');
    }

    public function index(StaffDatatable $datatable)
    {
        $datatable->builder()->setTableId('staffs-datatable')->orderBy(0, 'asc');

        $totalStaff = Staff::count();

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $departments = Department::orderBy('name')->get(['id', 'name']);

        $designations = Designation::orderBy('name')->get(['id', 'name']);

        return $datatable->render('staff.index', compact('totalStaff', 'branches', 'departments', 'designations'));
    }

    public function create()
    {
        $currentStaffCode = nextReferenceNumber('staff');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $departments = Department::orderBy('name')->get(['id', 'name']);

        $designations = Designation::orderBy('name')->get(['id', 'name']);

        return view('staff.create', compact('currentStaffCode', 'departments', 'designations', 'branches'));
    }

    public function store(StoreStaffRequest $request, CreateStaffDetail $createStaffDetail)
    {
        $staff = DB::transaction(function () use ($request, $createStaffDetail) {
            $staff = $createStaffDetail->execute($request->validated());

            return $staff;
        });

        return redirect()->route('staff.show', $staff->id);
    }

    public function show(Staff $staff)
    {
        $staff->load(['warehouse', 'department', 'designation', 'staffCompensations']);

        return view('staff.show', compact('staff'))->with('deleted', 'Deleted successfully.');
    }

    public function edit(Staff $staff)
    {
        $staff->load(['warehouse', 'department', 'designation', 'staffCompensations']);

        $branches = Warehouse::orderBy('name')->get(['id', 'name']);

        $departments = Department::orderBy('name')->get(['id', 'name']);

        $designations = Designation::orderBy('name')->get(['id', 'name']);

        return view('staff.edit', compact('staff', 'departments', 'designations', 'branches'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff, UpdateStaffDetail $updateStaffDetail)
    {
        $updateStaffDetail->execute($staff, $request->validated());

        return redirect()->route('staff.show', $staff->id)->with('successMessage', 'Updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $staff->softdelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
