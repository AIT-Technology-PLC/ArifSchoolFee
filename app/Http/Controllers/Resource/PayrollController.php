<?php

namespace App\Http\Controllers\Resource;

use App\Actions\ProcessPayrollAction;
use App\DataTables\PayrollDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePayrollRequest;
use App\Http\Requests\UpdatePayrollRequest;
use App\Models\Compensation;
use App\Models\Payroll;
use App\Notifications\PayrollCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Payroll Management');

        $this->authorizeResource(Payroll::class);
    }

    public function index(PayrollDatatable $datatable)
    {
        $datatable->builder()->setTableId('payrolls-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalPayrolls = Payroll::count();

        $totalApproved = Payroll::approved()->notPaid()->count();

        $totalNotApproved = Payroll::notApproved()->count();

        $totalPaid = Payroll::paid()->count();

        return $datatable->render('payrolls.index', compact('totalPayrolls', 'totalApproved', 'totalNotApproved', 'totalPaid'));
    }

    public function create()
    {
        $currentPayrollCode = nextReferenceNumber('payrolls');

        return view('payrolls.create', compact('currentPayrollCode'));
    }

    public function store(StorePayrollRequest $request)
    {
        $payroll = DB::transaction(function () use ($request) {
            $payroll = Payroll::create($request->validated());

            Notification::send(Notifiables::byNextActionPermission('Approve Payroll'), new PayrollCreated($payroll));

            return $payroll;
        });

        return redirect()->route('payrolls.show', $payroll->id);
    }

    public function show(Payroll $payroll, ProcessPayrollAction $processPayrollAction)
    {
        $compensations = Compensation::active()->get();

        $payrollSheet = $processPayrollAction->execute($payroll)->sortBy('employee_name');

        return view('payrolls.show', compact('payroll', 'compensations', 'payrollSheet'));
    }

    public function edit(Payroll $payroll)
    {
        if ($payroll->isPaid()) {
            return back()->with('failedMessage', 'You can not modify a payroll that is paid.');
        }

        if ($payroll->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a payroll that is approved.');
        }

        return view('payrolls.edit', compact('payroll'));
    }

    public function update(UpdatePayrollRequest $request, Payroll $payroll)
    {
        if ($payroll->isPaid()) {
            return back()->with('failedMessage', 'You can not modify a payroll that is paid.');
        }

        if ($payroll->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a payroll that is approved.');
        }

        $payroll->update($request->validated());

        return redirect()->route('payrolls.show', $payroll);
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->isPaid()) {
            return back()->with('failedMessage', 'You can not delete a payroll that is paid.');
        }

        if ($payroll->isApproved()) {
            return back()->with('failedMessage', 'You can not delete a payroll that is approved.');
        }

        $payroll->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
