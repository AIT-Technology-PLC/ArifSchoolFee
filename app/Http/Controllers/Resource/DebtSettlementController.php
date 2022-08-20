<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDebtSettlementRequest;
use App\Http\Requests\UpdateDebtSettlementRequest;
use App\Models\Debt;
use App\Models\DebtSettlement;

class DebtSettlementController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Debt Management');
    }

    public function create(Debt $debt)
    {
        $this->authorize('settle', $debt);

        if ($debt->isSettled()) {
            return back()->with('failedMessage', 'This debt is fully settled.');
        }

        return view('debt_settlements.create', compact('debt'));
    }

    public function store(Debt $debt, StoreDebtSettlementRequest $request)
    {
        $this->authorize('settle', $debt);

        if ($debt->isSettled()) {
            return redirect()->route('debts.show', $debt->id)->with('failedMessage', 'This debt is fully settled.');
        }

        if (($debt->debtSettlements()->sum('amount') + $request->validated('amount')) > $debt->debt_amount) {
            return redirect()->route('debts.show', $debt->id)
                ->with('failedMessage', 'The total amount settled has exceeded the debt amount.');
        }

        $debt->debtSettlements()->create($request->validated());

        return redirect()->route('debts.show', $debt->id);
    }

    public function edit(DebtSettlement $debtSettlement)
    {
        $this->authorize('settle', $debtSettlement->debt);

        if ($debtSettlement->debt->isSettled()) {
            return back()->with('failedMessage', 'This debt is fully settled.');
        }

        return view('debt_settlements.edit', compact('debtSettlement'));
    }

    public function update(DebtSettlement $debtSettlement, UpdateDebtSettlementRequest $request)
    {
        $this->authorize('settle', $debtSettlement->debt);

        if ($debtSettlement->debt->isSettled()) {
            return redirect()->route('debts.show', $debtSettlement->debt->id)->with('failedMessage', 'This debt is fully settled.');
        }

        $totalSettlementsAmount = $debtSettlement->debt->debtSettlements()->where('id', '<>', $debtSettlement->id)->sum('amount');

        if (($totalSettlementsAmount + $request->validated('amount')) > $debtSettlement->debt->debt_amount) {
            return redirect()->route('debts.show', $debtSettlement->debt->id)
                ->with('failedMessage', 'The total amount settled has exceeded the debt amount.');
        }

        $debtSettlement->update($request->validated());

        return redirect()->route('debts.show', $debtSettlement->debt->id);
    }

    public function destroy(DebtSettlement $debtSettlement)
    {
        $this->authorize('delete', $debtSettlement->debt);

        $debtSettlement->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
