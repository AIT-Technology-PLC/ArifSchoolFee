<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ReturnDatatable;
use App\DataTables\ReturnDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnRequest;
use App\Http\Requests\UpdateReturnRequest;
use App\Models\Gdn;
use App\Models\Returnn;
use App\Notifications\ReturnPrepared;
use App\Utilities\Notifiables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');

        $this->authorizeResource(Returnn::class, 'return');
    }

    public function index(ReturnDatatable $datatable)
    {
        $datatable->builder()->setTableId('returns-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalReturns = Returnn::count();

        $totalNotApproved = Returnn::notApproved()->count();

        $totalNotAdded = Returnn::approved()->notAdded()->count();

        $totalAdded = Returnn::added()->count();

        return $datatable->render('returns.index', compact('totalReturns', 'totalNotApproved', 'totalNotAdded', 'totalAdded'));
    }

    public function create()
    {
        $warehouses = authUser()->getAllowedWarehouses('add');

        $currentReturnCode = nextReferenceNumber('returns');

        $gdns = Gdn::getValidGdnsForReturn();

        return view('returns.create', compact('warehouses', 'currentReturnCode', 'gdns'));
    }

    public function store(StoreReturnRequest $request)
    {
        $return = DB::transaction(function () use ($request) {
            $return = Returnn::create($request->safe()->except('return'));

            $return->returnDetails()->createMany(
                Arr::where($request->validated('return'), fn($detail) => $detail['quantity'] > 0)
            );

            $return->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve Return'), new ReturnPrepared($return));

            return $return;
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function show(Returnn $return, ReturnDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('return-details-datatable');

        $return->load(['returnDetails.product', 'returnDetails.warehouse', 'returnDetails.merchandiseBatch', 'customer', 'gdn.customer', 'customFieldValues.customField']);

        return $datatable->render('returns.show', compact('return'));
    }

    public function edit(Returnn $return)
    {
        $warehouses = authUser()->getAllowedWarehouses('add');

        $gdns = Gdn::getValidGdnsForReturn($return->gdn_id);

        $return->load(['returnDetails.product', 'returnDetails.warehouse', 'returnDetails.merchandiseBatch']);

        return view('returns.edit', compact('return', 'warehouses', 'gdns'));
    }

    public function update(UpdateReturnRequest $request, Returnn $return)
    {
        if ($return->isAdded()) {
            return redirect()->route('returns.show', $return->id)
                ->with('failedMessage', 'Added returns cannot be edited.');
        }

        DB::transaction(function () use ($request, $return) {
            $return->update($request->safe()->except('return'));

            $return->returnDetails()->forceDelete();

            $return->returnDetails()->createMany(
                Arr::where($request->validated('return'), fn($detail) => $detail['quantity'] > 0)
            );

            $return->createCustomFields($request->validated('customField'));
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function destroy(Returnn $return)
    {
        abort_if($return->isAdded(), 403);

        abort_if($return->isApproved() && !authUser()->can('Delete Approved Return'), 403);

        $return->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
