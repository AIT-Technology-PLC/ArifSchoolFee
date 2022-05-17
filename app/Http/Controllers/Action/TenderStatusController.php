<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Imports\TenderStatusImport;
use Maatwebsite\Excel\Facades\Excel;

class TenderStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }
    public function import()
    {
        Excel::import(new TenderStatusImport, request()->file('file'));

        return redirect()->route('tenders-tatus.index');
    }
}