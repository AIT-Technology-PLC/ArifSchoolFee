<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\TenderLotDetail;

class TenderLotDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function destroy(TenderLotDetail $tenderLotDetail)
    {
        $this->authorize('delete', $tenderLotDetail->tenderLot->tender);

        $tenderLotDetail->forceDelete();

        return back()->with('lotDetailDeleted', 'Deleted successfully.');
    }
}
