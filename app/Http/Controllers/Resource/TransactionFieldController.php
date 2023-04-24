<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\TransactionField;

class TransactionFieldController extends Controller
{
    public function destroy(TransactionField $transactionField)
    {
        abort_if(!$transactionField->transaction->pad->isEnabled(), 403);

        $this->authorize('delete', $transactionField->transaction);

        abort_if(!$transactionField->transaction->canBeDeleted(), 403);

        TransactionField::where('transaction_id', $transactionField->transaction_id)->where('line', $transactionField->line)->forceDelete();

        return back()->with('successMessage', 'Deleted Successfully');
    }
}
