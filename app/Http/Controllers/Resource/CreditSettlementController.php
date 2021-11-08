<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditSettlementRequest;
use App\Http\Requests\UpdateCreditSettlementRequest;
use App\Models\Credit;
use App\Models\CreditSettlement;

class CreditSettlementController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Credit Management');
    }

    public function create(Credit $credit)
    {
        $this->authorize('settle', $credit);

        if ($credit->isSettled()) {
            return back()->with('failedMessage', 'This credit is fully settled.');
        }

        return view('credit_settlements.create', compact('credit'));
    }

    public function store(Credit $credit, StoreCreditSettlementRequest $request)
    {
        $this->authorize('settle', $credit);

        if ($credit->isSettled()) {
            return redirect()->route('credits.show', $credit->id)->with('failedMessage', 'This credit is fully settled.');
        }

        if (($credit->creditSettlements()->sum('amount') + $request->amount) > $credit->credit_amount) {
            return redirect()->route('credits.show', $credit->id)
                ->with('failedMessage', 'The total amount settled has exceeded the credit amount.');
        }

        $credit->creditSettlements()->create($request->validated());

        return redirect()->route('credits.show', $credit->id);
    }

    public function edit(CreditSettlement $creditSettlement)
    {
        $this->authorize('settle', $creditSettlement->credit);

        if ($creditSettlement->credit->isSettled()) {
            return back()->with('failedMessage', 'This credit is fully settled.');
        }

        return view('credit_settlements.edit', compact('creditSettlement'));
    }

    public function update(CreditSettlement $creditSettlement, UpdateCreditSettlementRequest $request)
    {
        $this->authorize('settle', $creditSettlement->credit);

        if ($creditSettlement->credit->isSettled()) {
            return redirect()->route('credits.show', $creditSettlement->credit->id)->with('failedMessage', 'This credit is fully settled.');
        }

        $totalSettlementsAmount = $creditSettlement->credit->creditSettlements()->where('id', '<>', $creditSettlement->id)->sum('amount');

        if (($totalSettlementsAmount + $request->amount) > $creditSettlement->credit->credit_amount) {
            return redirect()->route('credits.show', $creditSettlement->credit->id)
                ->with('failedMessage', 'The total amount settled has exceeded the credit amount.');
        }

        $creditSettlement->update($request->validated());

        return redirect()->route('credits.show', $creditSettlement->credit->id);
    }

    public function destroy(CreditSettlement $creditSettlement)
    {
        $this->authorize('delete', $creditSettlement->credit);

        $creditSettlement->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
