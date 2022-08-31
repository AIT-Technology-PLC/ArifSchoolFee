<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ExpenseCategoryDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Expense Management');

        $this->authorizeResource(ExpenseCategory::class);
    }

    public function index(ExpenseCategoryDatatable $datatable)
    {
        $datatable->builder()->setTableId('expense-categories-datatable')->orderBy(1, 'asc');

        $totalExpenseCategories = ExpenseCategory::count();

        return $datatable->render('expense-categories.index', compact('totalExpenseCategories'));
    }

    public function create()
    {
        return view('expense-categories.create');
    }

    public function store(StoreExpenseCategoryRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('expenseCategory') as $expenseCategory) {
                ExpenseCategory::create($expenseCategory);
            }
        });

        return redirect()->route('expense-categories.index')->with('successMessage', 'New Expense Category are added.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense-categories.edit', compact('expenseCategory'));
    }

    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        $expenseCategory->update($request->validated());

        return redirect()->route('expense-categories.index');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}