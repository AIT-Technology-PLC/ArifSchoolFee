<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\TransactionField;

class TransactionFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Pad Management');
    }

    public function destroy(TransactionField $transactionField)
    {
        $this->authorize('delete', $transactionField->transaction);

        TransactionField::where('line', $transactionField->line)->forceDelete();

        return back()->with('successMessage', 'Deleted Successfully');
    }
}
