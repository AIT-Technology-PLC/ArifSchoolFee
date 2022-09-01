<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ExpenseDatatable;
use App\DataTables\ExpenseDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Supplier;
use App\Notifications\ExpenseCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Management');

        $this->authorizeResource(Expense::class);
    }

    public function index(ExpenseDatatable $datatable)
    {
        $datatable->builder()->setTableId('expenses-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalExpenses = Expense::count();

        $totalApproved = Expense::approved()->count();

        $totalNotApproved = Expense::notApproved()->count();

        return $datatable->render('expenses.index', compact('totalExpenses', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $expenseCategories = ExpenseCategory::orderBy('name')->get(['id', 'name']);

        $currentExpenseCode = nextReferenceNumber('expenses');

        return view('expenses.create', compact('suppliers', 'currentExpenseCode', 'expenseCategories'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $expense = DB::transaction(function () use ($request) {
            $expense = Expense::create($request->safe()->except('expense'));

            $expense->expenseDetails()->createMany($request->validated('expense'));

            Notification::send(Notifiables::byNextActionPermission('Approve Expense'), new ExpenseCreated($expense));

            return $expense;
        });

        return redirect()->route('expenses.show', $expense->id);
    }

    public function show(Expense $expense, ExpenseDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('expense-details-datatable');

        return $datatable->render('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an expense that is approved.');
        }

        $expense->load(['expenseDetails']);

        $expenseCategories = ExpenseCategory::orderBy('name')->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        return view('expenses.edit', compact('expense', 'suppliers', 'expenseCategories'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        if ($expense->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an expense that is approved.');
        }

        DB::transaction(function () use ($request, $expense) {
            $expense->update($request->safe()->except('expense'));

            $expense->expenseDetails()->forceDelete();

            $expense->expenseDetails()->createMany($request->validated('expense'));
        });

        return redirect()->route('expenses.show', $expense->id);
    }

    public function destroy(Expense $expense)
    {
        if ($expense->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an expense that is approved.');
        }

        $expense->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}