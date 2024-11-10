<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\BranchDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Warehouse;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Branch Management');

        $this->authorizeResource(Warehouse::class, 'branch');
    }

    public function index(BranchDatatable $datatable)
    {
        $datatable->builder()->setTableId('branches-datatable')->orderBy(1, 'asc');

        $totalBranches = Warehouse::count();

        $totalActiveBranches = Warehouse::active()->count();

        $totalInActiveBranches = $totalBranches - $totalActiveBranches;

        return $datatable->render('branches.index', compact('totalBranches', 'totalActiveBranches', 'totalInActiveBranches'));
    }

    public function create()
    {
        if (limitReached('branch', Warehouse::active()->count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));
        }

        return view('branches.create');
    }

    public function store(StoreBranchRequest $request)
    {
        if (limitReached('branch', Warehouse::active()->count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));
        }

        Warehouse::firstOrCreate(
            $request->safe()->only(['name'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('branches.index')->with('successMessage', 'New Branch Created Successfully.');
    }

    public function edit(Warehouse $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, Warehouse $branch)
    {
        if (! $branch->isActive() && $request->validated('is_active') && limitReached('branch', Warehouse::active()->count())) {
            $branch->update($request->safe()->except('is_active'));

            return redirect()->route('branches.index')->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));
        }

        $branch->update($request->validated());

        return redirect()->route('branches.index')->with('successMessage', 'Updated Successfully.');
    }
}
