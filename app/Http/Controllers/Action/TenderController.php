<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use Barryvdh\DomPDF\Facade\Pdf;

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

        return Pdf::loadView('tenders.print', compact('tender'))->stream();
    }
}
