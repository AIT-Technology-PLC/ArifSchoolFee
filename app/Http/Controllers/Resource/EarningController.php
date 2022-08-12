<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\EarningDatatable;
use App\DataTables\EarningDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEarningRequest;
use App\Http\Requests\UpdateEarningRequest;
use App\Models\Earning;
use App\Models\EarningCategory;
use App\Models\User;
use App\Notifications\EarningCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class EarningController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Earning Management');

        $this->authorizeResource(Earning::class);
    }

    public function index(EarningDatatable $datatable)
    {
        $datatable->builder()->setTableId('earnings-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalEarnings = Earning::count();

        $totalApproved = Earning::approved()->count();

        $totalNotApproved = Earning::notApproved()->count();

        return $datatable->render('earnings.index', compact('totalEarnings', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $currentEarningCode = nextReferenceNumber('earnings');

        $categories = EarningCategory::orderBy('name')->get(['id', 'name']);

        $users = User::whereIn('warehouse_id', authUser()->getAllowedWarehouses('hr')->pluck('id'))->with('employee')->orderBy('name')->get();

        return view('earnings.create', compact('currentEarningCode', 'categories', 'users'));
    }

    public function store(StoreEarningRequest $request)
    {
        $earningDetails = $request->validated('earning');

        foreach ($earningDetails as &$earningDetail) {
            data_set($earningDetail, 'employeeEarnings.*.employee_id', $earningDetail['employee_id']);
        }

        $earningDetails = data_get($earningDetails, '*.employeeEarnings');

        $earning = DB::transaction(function () use ($request, $earningDetails) {
            $earning = Earning::create($request->safe()->except('earning'));

            foreach ($earningDetails as $earningDetail) {
                $earning->earningDetails()->createMany($earningDetail);
            }

            Notification::send(Notifiables::byNextActionPermission('Approve Earning'), new EarningCreated($earning));

            return $earning;
        });

        return redirect()->route('earnings.show', $earning->id);
    }

    public function show(Earning $earning, EarningDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('earning-details-datatable');

        return $datatable->render('earnings.show', compact('earning'));
    }

    public function edit(Earning $earning)
    {
        if ($earning->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an earning that is approved.');
        }

        $categories = EarningCategory::orderBy('name')->get(['id', 'name']);

        $users = User::whereIn('warehouse_id', authUser()->getAllowedWarehouses('hr')->pluck('id'))->with('employee')->orderBy('name')->get();

        $earningDetails = array_values($earning->load(['earningDetails'])->earningDetails->groupBy('employee_id')->toArray());

        return view('earnings.edit', compact('earning', 'categories', 'users', 'earningDetails'));
    }

    public function update(UpdateEarningRequest $request, Earning $earning)
    {
        if ($earning->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an earning that is approved.');
        }

        $earningDetails = $request->validated('earning');

        foreach ($earningDetails as &$earningDetail) {
            data_set($earningDetail, 'employeeEarnings.*.employee_id', $earningDetail['employee_id']);
        }

        $earningDetails = data_get($earningDetails, '*.employeeEarnings');

        DB::transaction(function () use ($request, $earning, $earningDetails) {
            $earning->update($request->safe()->except('earning'));

            $earning->earningDetails()->forceDelete();

            foreach ($earningDetails as $earningDetail) {
                $earning->earningDetails()->createMany($earningDetail);
            }
        });

        return redirect()->route('earnings.show', $earning->id);
    }

    public function destroy(Earning $earning)
    {
        if ($earning->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an earning that is approved.');
        }

        $earning->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
