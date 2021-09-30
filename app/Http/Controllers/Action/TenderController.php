<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Tender;

class TenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function printed(Tender $tender)
    {
        $this->authorize('view', $tender);

        $tender->load(['tenderChecklists.generalTenderChecklist']);

        return \PDF::loadView('tenders.print', compact('tender'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}
