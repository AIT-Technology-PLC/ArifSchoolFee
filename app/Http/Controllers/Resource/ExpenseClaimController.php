<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ExpenseClaimDatatable;
use App\DataTables\ExpenseClaimDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseClaimRequest;
use App\Http\Requests\UpdateExpenseClaimRequest;
use App\Models\ExpenseClaim;
use App\Models\User;
use App\Notifications\ExpenseClaimCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ExpenseClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Claim');

        $this->authorizeResource(ExpenseClaim::class);
    }

    public function index(ExpenseClaimDatatable $datatable)
    {
        $datatable->builder()->setTableId('expense-claims-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalExpenseClaims = ExpenseClaim::count();

        $totalApproved = ExpenseClaim::approved()->notRejected()->count();

        $totalNotApproved = ExpenseClaim::notApproved()->notRejected()->count();

        $totalRejected = ExpenseClaim::rejected()->count();

        return $datatable->render('expense-claims.index', compact('totalExpenseClaims', 'totalApproved', 'totalNotApproved', 'totalRejected'));
    }

    public function create()
    {
        $users = User::whereRelation('employee', 'company_id', '=', userCompany()->id)->with('employee')->orderBy('name')->get();

        return view('expense-claims.create', compact('users'));
    }

    public function store(StoreExpenseClaimRequest $request)
    {
        $expenseClaim = DB::transaction(function () use ($request) {
            $expenseClaim = ExpenseClaim::create($request->safe()->except('expenseClaim'));

            $expenseClaim->expenseClaimDetails()->createMany($request->validated('expenseClaim'));

            Notification::send(Notifiables::byNextActionPermission('Approve Expense Claim'), new ExpenseClaimCreated($expenseClaim));

            return $expenseClaim;
        });

        return redirect()->route('expense-claims.show', $expenseClaim->id);
    }

    public function show(ExpenseClaim $expenseClaim, ExpenseClaimDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('expense-claim-details-datatable');

        return $datatable->render('expense-claims.show', compact('expenseClaim'));
    }

    public function edit(ExpenseClaim $expenseClaim)
    {
        if ($expenseClaim->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an expense claims that is approved.');
        }

        if ($expenseClaim->isRejected()) {
            return back()->with('failedMessage', 'You can not modify an expense claims that is rejected.');
        }

        return view('expense-claims.edit', compact('expenseClaim'));
    }

    public function update(UpdateExpenseClaimRequest $request, ExpenseClaim $expenseClaim)
    {
        if ($expenseClaim->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an expense claims that is approved.');
        }

        if ($expenseClaim->isRejected()) {
            return back()->with('failedMessage', 'You can not modify an expense claims that is rejected.');
        }

        DB::transaction(function () use ($request, $expenseClaim) {
            $expenseClaim->update($request->safe()->except('expenseClaim'));

            $expenseClaim->expenseClaimDetails()->forceDelete();

            $expenseClaim->expenseClaimDetails()->createMany($request->validated('expenseClaim'));
        });

        return redirect()->route('expense-claims.show', $expenseClaim->id);
    }

    public function destroy(ExpenseClaim $expenseClaim)
    {
        if ($expenseClaim->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an expense claims that is approved.');
        }

        if ($expenseClaim->isRejected()) {
            return back()->with('failedMessage', 'You can not delete an expense claims that is rejected.');
        }

        $expenseClaim->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}