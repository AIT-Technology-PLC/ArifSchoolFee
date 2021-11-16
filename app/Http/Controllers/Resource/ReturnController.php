<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnRequest;
use App\Http\Requests\UpdateReturnRequest;
use App\Models\Returnn;
use App\Notifications\ReturnPrepared;
use App\Services\NextReferenceNumService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');

        $this->authorizeResource(Returnn::class, 'return');
    }

    public function index()
    {
        $returns = Returnn::with(['returnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'customer'])->latest('code')->get();

        $totalReturns = Returnn::count();

        $totalNotApproved = Returnn::notApproved()->count();

        $totalNotAdded = Returnn::approved()->notAdded()->count();

        $totalAdded = Returnn::added()->count();

        return view('returns.index', compact('returns', 'totalReturns', 'totalNotApproved', 'totalNotAdded', 'totalAdded'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('add');

        $currentReturnCode = NextReferenceNumService::table('returns');

        return view('returns.create', compact('warehouses', 'currentReturnCode'));
    }

    public function store(StoreReturnRequest $request)
    {
        $return = DB::transaction(function () use ($request) {
            $return = Returnn::create($request->except('return'));

            $return->returnDetails()->createMany($request->return);

            Notification::send(notifiables('Approve Return'), new ReturnPrepared($return));

            return $return;
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function show(Returnn $return)
    {
        $return->load(['returnDetails.product', 'returnDetails.warehouse', 'customer']);

        return view('returns.show', compact('return'));
    }

    public function edit(Returnn $return)
    {
        $warehouses = auth()->user()->getAllowedWarehouses('add');

        $return->load(['returnDetails.product', 'returnDetails.warehouse']);

        return view('returns.edit', compact('return', 'warehouses'));
    }

    public function update(UpdateReturnRequest $request, Returnn $return)
    {
        if ($return->isApproved()) {
            return redirect()->route('returns.show', $return->id)
                ->with('failedMessage', 'Approved returns cannot be edited.');
        }

        DB::transaction(function () use ($request, $return) {
            $return->update($request->except('return'));

            for ($i = 0; $i < count($request->return); $i++) {
                $return->returnDetails[$i]->update($request->return[$i]);
            }
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function destroy(Returnn $return)
    {
        abort_if($return->isAdded(), 403);

        abort_if($return->isApproved() && !auth()->user()->can('Delete Approved Return'), 403);

        $return->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
