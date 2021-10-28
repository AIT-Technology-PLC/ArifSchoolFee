<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\TenderDetail;

class TenderDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function destroy(TenderDetail $tenderDetail)
    {
        $this->authorize('delete', $tenderDetail->tender);

        $tenderDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
