<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\TransactionField;

class TransactionFieldController extends Controller
{
    public function destroy(TransactionField $transactionField)
    {
        TransactionField::where('line', $transactionField->line)->forceDelete();

        return back()->with('successMessage', 'Deleted Successfully');
    }
}
