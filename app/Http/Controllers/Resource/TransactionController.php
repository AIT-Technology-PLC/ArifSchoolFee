<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TransactionDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Pad;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Pad Management');
    }

    public function index(Pad $pad, TransactionDatatable $datatable)
    {
        abort_if(!$pad->isEnabled(), 403);

        $this->authorize('viewAny', $pad);

        $datatable->builder()->setTableId($pad->label . '-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

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
                ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'approved_by'))
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
                ->whereDoesntHave('transactionFields', fn($q) => $q->where('key', '=', 'approved_by'))
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
        $pad->load(['padFields.padRelation']);

        $currentReferenceCode = nextReferenceNumber('transactions');

        return view('transactions.create', compact('pad', 'currentReferenceCode'));
    }

    public function store(Pad $pad, StoreTransactionRequest $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
