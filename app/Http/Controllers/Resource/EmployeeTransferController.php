<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\EmployeeTransferDatatable;
use App\DataTables\EmployeeTransferDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeTransferRequest;
use App\Http\Requests\UpdateEmployeeTransferRequest;
use App\Models\EmployeeTransfer;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\EmployeeTransferCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class EmployeeTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Employee Transfer');

        $this->authorizeResource(EmployeeTransfer::class);
    }

    public function index(EmployeeTransferDatatable $datatable)
    {
        $datatable->builder()->setTableId('employee-transfer-datatable')->orderBy(1, 'desc');

        $totalEmployeeTransfers = EmployeeTransfer::count();

        $totalApproved = EmployeeTransfer::approved()->count();

        $totalNotApproved = EmployeeTransfer::notApproved()->count();

        return $datatable->render('employee-transfers.index', compact('totalEmployeeTransfers', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $currentEmployeeTransferNo = nextReferenceNumber('employee_transfers');

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        return view('employee-transfers.create', compact('currentEmployeeTransferNo', 'users', 'warehouses'));
    }

    public function store(StoreEmployeeTransferRequest $request)
    {
        $employeeTransfer = DB::transaction(function () use ($request) {
            $employeeTransfer = EmployeeTransfer::create($request->safe()->except('employeeTransfer'));

            $employeeTransfer->employeeTransferDetails()->createMany($request->validated('employeeTransfer'));

            Notification::send(Notifiables::byNextActionPermission('Approve Employee Transfer'), new EmployeeTransferCreated($employeeTransfer));

            return $employeeTransfer;
        });

        return redirect()->route('employee-transfers.show', $employeeTransfer->id);
    }

    public function show(EmployeeTransfer $employeeTransfer, EmployeeTransferDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('employee-transfer-details-datatable');

        return $datatable->render('employee-transfers.show', compact('employeeTransfer'));
    }

    public function edit(EmployeeTransfer $employeeTransfer)
    {
        if ($employeeTransfer->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an employee transfer that is approved.');
        }

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        $employeeTransfer->load(['employeeTransferDetails.employee.user', 'employeeTransferDetails.warehouse']);

        return view('employee-transfers.edit', compact('employeeTransfer', 'users', 'warehouses'));
    }

    public function update(UpdateEmployeeTransferRequest $request, EmployeeTransfer $employeeTransfer)
    {
        if ($employeeTransfer->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an employee transfer that is approved.');
        }

        DB::transaction(function () use ($request, $employeeTransfer) {
            $employeeTransfer->update($request->safe()->except('employeeTransfer'));

            $employeeTransfer->employeeTransferDetails()->forceDelete();

            $employeeTransfer->employeeTransferDetails()->createMany($request->validated('employeeTransfer'));
        });

        return redirect()->route('employee-transfers.show', $employeeTransfer->id);
    }

    public function destroy(EmployeeTransfer $employeeTransfer)
    {
        if ($employeeTransfer->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an employee transfer that is approved.');
        }

        $employeeTransfer->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
