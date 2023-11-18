<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TransactionDatatable;
use App\DataTables\TransactionFieldDatatable;
use App\Http\Controllers\Controller;
use App\Models\Pad;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index(Pad $pad, TransactionDatatable $datatable)
    {
        abort_if(!$pad->isEnabled(), 403);

        $this->authorize('viewAny', [Transaction::class, $pad]);

        $datatable->builder()->setTableId(str($pad->name)->slug() . '-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $transactions = Transaction::where('pad_id', $pad->id)->get();

        $pad->load(['padStatuses' => fn($q) => $q->active()]);

        $data = [];

        if ($pad->isInventoryOperationAdd()) {
            $data['totalAdded'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereRelation('transactionFields', 'key', '=', 'added_by')
                ->count();

            $data['totalApproved'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereRelation('transactionFields', 'key', '=', 'approved_by')
                ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'added_by'))
                ->count();

            $data['totalNotApproved'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'approved_by')->orWhere('key', '=', 'added_by'))
                ->count();
        }

        if ($pad->isInventoryOperationSubtract()) {
            $data['totalSubtracted'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereRelation('transactionFields', 'key', '=', 'subtracted_by')
                ->count();

            $data['totalApproved'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereRelation('transactionFields', 'key', '=', 'approved_by')
                ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'subtracted_by'))
                ->count();

            $data['totalNotApproved'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'approved_by')->orWhere('key', '=', 'subtracted_by'))
                ->count();
        }

        if ($pad->isApprovable() && $pad->isInventoryOperationNone()) {
            $data['totalApproved'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereRelation('transactionFields', 'key', '=', 'approved_by')
                ->count();

            $data['totalNotApproved'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'approved_by'))
                ->count();
        }

        return $datatable->render('transactions.index', compact('pad', 'transactions', 'data'));
    }

    public function create(Pad $pad)
    {
        abort_if(!$pad->isEnabled(), 403);

        $this->authorize('create', [Transaction::class, $pad]);

        return view('transactions.create', compact('pad'));
    }

    public function show(Transaction $transaction, TransactionFieldDatatable $datatable)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('view', $transaction);

        $transaction->load(['pad.padStatuses', 'transactionFields']);

        $datatable->builder()->setTableId(str($transaction->pad->name)->slug()->append('-details-datatable'));

        $masterTransactionFields = $transaction->transactionFields()->with('padField.padRelation')->masterFields()->get();

        $hasDetails = $transaction->transactionFields()->detailFields()->exists();

        $hasDescriptionBox = $transaction->pad->padFields()->masterFields()->where('label', 'Description')->exists();

        return $datatable->render('transactions.show', compact('transaction', 'masterTransactionFields', 'hasDetails', 'hasDescriptionBox'));
    }

    public function edit(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('update', $transaction);

        $transaction->load('pad');

        $hasDescriptionBox = $transaction->pad->padFields()->masterFields()->where('label', 'Description')->exists();

        abort_if(!$transaction->canBeEdited() && !$hasDescriptionBox, 403);

        return view('transactions.edit', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('delete', $transaction);

        abort_if(!$transaction->canBeDeleted(), 403);

        $transaction->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
