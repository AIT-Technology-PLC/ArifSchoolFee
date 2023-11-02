<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\ExchangeDetail;

class ExchangeDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Exchange Management');
    }

    public function destroy(ExchangeDetail $exchangeDetail)
    {
        $this->authorize('delete', $exchangeDetail->exchange);

        if ($exchangeDetail->exchange->isExecuted()) {
            return back()->with('failedMessage', 'You can not delete an exchange that is executed.');
        }

        if ($exchangeDetail->exchange->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an exchange that is approved.');
        }

        $exchangeDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
