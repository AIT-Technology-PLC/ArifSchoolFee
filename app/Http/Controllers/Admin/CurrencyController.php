<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CurrencyDatatable;
use App\DataTables\Admin\CurrencyHistoryDatatable;
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

        $validated = $request->validated();

        $validated['rate_source'] = $validated['exchange_rate'] !== null ? 'manual' : null;

        $currency = Currency::create($validated);

        if ($validated['exchange_rate'] !== null) {
            $currency->currencyHistories()->create($validated);
        }

        return redirect()->route('admin.currencies.show', $currency->id)->with('successMessage', 'Currency created successfully');
    }

    public function show(Currency $currency, CurrencyHistoryDatatable $datatable)
    {
        $datatable->builder()->setTableId('currency-histories-datatable');

        return $datatable->render('admin.currencies.show', compact('currency'));
    }

    public function edit(Currency $currency)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $validated = $request->validated();

        $validated['rate_source'] = $validated['exchange_rate'] !== null ? 'manual' : $currency->rate_source;

        $currency->update($validated);

        if ($validated['exchange_rate'] !== null) {
            $currency->currencyHistories()->create($validated);
        }

        return redirect()->route('admin.currencies.show', $currency->id)->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(Currency $currency)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $currency->delete();

        return back()->with('successMessage', 'Deleted successfully.');
    }
}
