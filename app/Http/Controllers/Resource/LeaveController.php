<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\LeaveDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequest;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Leave Management');

        $this->authorizeResource(Leave::class);
    }

    public function index(LeaveDatatable $datatable)
    {
        $datatable->builder()->setTableId('leaves-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalLeaves = Leave::count();

        $totalApproved = Leave::approved()->notCancelled()->count();

        $totalNotApproved = Leave::notApproved()->notCancelled()->count();

        $totalCancelled = Leave::cancelled()->count();

        return $datatable->render('leaves.index', compact('totalLeaves', 'totalApproved', 'totalNotApproved', 'totalCancelled'));

    }

    public function create()
    {
        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        return view('leaves.create', compact('users'));

    }

    public function store(StoreLeaveRequest $request)
    {
        $leave = DB::transaction(function () use ($request) {
            $leave = Leave::create($request->safe()->except('leave'));

            $leave->attendanceDetails()->createMany($request->validated('leave'));

            return $leave;
        });

        return redirect()->route('leaves.show', $leave->id);
    }

    public function edit(Leave $leave)
    {
        if ($leave->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an leave that is approved.');
        }

        if ($leave->isCancelled()) {
            return back()->with('failedMessage', 'You can not modify an leave that is cancelled.');
        }

        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        return view('leaves.edit', compact('leave', 'users'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Leave $leave)
    {
        if ($leave->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an leave that is approved.');
        }

        if ($leave->isCancelled()) {
            return back()->with('failedMessage', 'You can not delete an leave that is cancelled.');
        }

        $leave->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}