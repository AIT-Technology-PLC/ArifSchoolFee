<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TransactionDatatable;
use App\DataTables\TransactionFieldDatatable;
use App\Http\Controllers\Controller;
use App\Models\Pad;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Pad Management');
    }

    public function index(Pad $pad, TransactionDatatable $datatable)
    {
        abort_if(!$pad->isEnabled(), 403);

        $this->authorize('viewAny', [Transaction::class, $pad]);

        $datatable->builder()->setTableId(str($pad->name)->slug() . '-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $transactions = Transaction::where('pad_id', $pad->id)->get();

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

        if ($pad->isCancellable()) {
            $data['totalCancelled'] = Transaction::query()
                ->where('pad_id', $pad->id)
                ->whereRelation('transactionFields', 'key', '=', 'cancelled_by')
                ->count();
        }

        return $datatable->render('transactions.index', compact('pad', 'transactions', 'data'));
    }

    public function create(Pad $pad)
    {
        $this->authorize('create', [Transaction::class, $pad]);

        return view('transactions.create', compact('pad'));
    }

    public function show(Transaction $transaction, TransactionFieldDatatable $datatable)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['pad', 'transactionFields']);

        $datatable->builder()->setTableId(str($transaction->pad->name)->slug()->append('-details-datatable'));

        $masterTransactionFields = $transaction->transactionFields()->with('padField.padRelation')->masterFields()->get();

        return $datatable->render('transactions.show', compact('transaction', 'masterTransactionFields'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->load('pad');

        return view('transactions.edit', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        abort_if(
            $transaction->isApproved() || $transaction->isCancelled() ||
            $transaction->isClosed() || $transaction->isAdded() || $transaction->isSubtracted(),
            403
        );

        $transaction->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
