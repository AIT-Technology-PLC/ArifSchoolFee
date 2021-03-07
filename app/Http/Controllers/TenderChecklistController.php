<?php

namespace App\Http\Controllers;

use App\Models\TenderChecklist;
use Illuminate\Http\Request;

class TenderChecklistController extends Controller
{
    public function __construct()
    {
        //
    }

    public function edit(TenderChecklist $tenderChecklist)
    {

    }

    public function update(TenderChecklist $tenderChecklist, Request $request)
    {

    }

    public function destroy(TenderChecklist $tenderChecklist)
    {
        $tenderChecklist->forceDelete();
    }
}
