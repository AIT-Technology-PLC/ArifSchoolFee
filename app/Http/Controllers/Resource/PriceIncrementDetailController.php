<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\PriceIncrementDetail;

class PriceIncrementDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Price Increment');
    }

    public function destroy(PriceIncrementDetail $priceIncrementDetail)
    {
        $this->authorize('delete', $priceIncrementDetail->priceIncrement);

        if ($priceIncrementDetail->priceIncrement->isApproved()) {
            return back()->with('failedMessage', 'You can not delete a price increment that is approved.');
        }

        $priceIncrementDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}