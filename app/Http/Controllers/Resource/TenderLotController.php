<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\TenderLot;

class TenderLotController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function destroy(TenderLot $tenderLot)
    {
        $this->authorize('delete', $tenderLot->tender);

        abort_if($tenderLot->tender->tenderLots->count() == 1, 403);

        $tenderLot->forceDelete();

        return back()->with('lotDeleted', 'Deleted successfully.');
    }
}
