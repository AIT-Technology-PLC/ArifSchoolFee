<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Imports\TenderStatusImport;
use App\Models\Tender;

class TenderStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');

        $this->authorizeResource(TenderStatus::class);
    }

    public function import()
    {
        $this->authorize(Tender::class, 'import');

        (new TenderStatusImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}