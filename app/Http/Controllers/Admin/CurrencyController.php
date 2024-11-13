<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CurrencyDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCurrencyRequest;
use App\Http\Requests\Admin\UpdateCurrencyRequest;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function index(CurrencyDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $datatable->builder()->setTableId('currencies-datatable')->orderBy(1, 'asc');

        $totalCurrency = Currency::count();

        return $datatable->render('admin.currencies.index', compact('totalCurrency'));
    }

    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.currencies.create');
    }

    public function store(StoreCurrencyRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        Currency::create($request->validated());

        return redirect()->route('admin.currencies.index')->with('successMessage', 'Currency created successfully');
    }

    public function edit(Currency $currency)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $currency->update($request->validated());

        return redirect()->route('admin.currencies.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(Currency $currency)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $currency->delete();

        return back()->with('successMessage', 'Deleted successfully.');
    }
}
