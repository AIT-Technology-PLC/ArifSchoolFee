<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ExchangeDatatable;
use App\DataTables\ExchangeDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExchangeRequest;
use App\Http\Requests\UpdateExchangeRequest;
use App\Models\Exchange;
use App\Models\Gdn;
use App\Models\Sale;
use App\Notifications\ExchangeCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ExchangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Exchange Management');

        $this->authorizeResource(Exchange::class, 'exchange');
    }

    public function index(ExchangeDatatable $datatable)
    {
        $datatable->builder()->setTableId('exchanges-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalExchanges = Exchange::count();

        $totalApproved = Exchange::approved()->notExecuted()->count();

        $totalNotApproved = Exchange::notApproved()->count();

        $totalExecuted = Exchange::executed()->count();

        return $datatable->render('exchanges.index', compact('totalExchanges', 'totalExecuted', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $warehouses = authUser()->getAllowedWarehouses('sale');

        $gdns = Gdn::getValidGdnsForReturn();

        $sales = Sale::getValidSalesForReturn();

        $currentExchangeCode = nextReferenceNumber('exchanges');

        return view('exchanges.create', compact('gdns', 'warehouses', 'sales', 'currentExchangeCode'));
    }

    public function store(StoreExchangeRequest $request)
    {
        $modelClass = !is_null($request->validated('gdn_id')) ? Gdn::class : Sale::class;

        $exchangeableId = !is_null($request->validated('gdn_id')) ? $request->validated('gdn_id') : $request->validated('sale_id');

        $model = new $modelClass();

        $exchange = DB::transaction(function () use ($request, $model, $exchangeableId) {
            $exchange = $model->exchange()->create($request->safe()->except('exchange'));

            $exchange->exchangeable_id = $exchangeableId;
            $exchange->save();

            $exchange->exchangeDetails()->createMany($request->validated('exchange'));

            $exchange->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve Exchange'), new ExchangeCreated($exchange));

            return $exchange;
        }, 2);

        return redirect()->route('exchanges.show', $exchange->id);
    }

    public function show(Exchange $exchange, ExchangeDetailDatatable $datatable)
    {
        $model = get_class($exchange->exchangeable) == "App\Models\Sale" ? "sale" : "gdn";

        $datatable->builder()->setTableId('exchange-details-datatable');

        $exchange->load(['exchangeDetails.product', 'exchangeDetails.warehouse', 'exchangeDetails.merchandiseBatch', 'customFieldValues.customField']);

        return $datatable->render('exchanges.show', compact('exchange', 'model'));
    }

    public function edit(Exchange $exchange)
    {
        if ($exchange->isExecuted()) {
            return back()->with('failedMessage', 'You can not modify a exchange that is executed.');
        }

        if ($exchange->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a exchange that is approved.');
        }

        $warehouses = authUser()->getAllowedWarehouses('sale');

        $gdns = Gdn::getValidGdnsForReturn();

        $sales = Sale::getValidSalesForReturn();

        $exchange->load(['exchangeDetails.product', 'exchangeDetails.warehouse', 'exchangeDetails.merchandiseBatch']);

        return view('exchanges.edit', compact('exchange', 'warehouses', 'gdns', 'sales'));
    }

    public function update(UpdateExchangeRequest $request, Exchange $exchange)
    {
        if ($exchange->isExecuted()) {
            return back()->with('failedMessage', 'You can not modify a exchange that is executed.');
        }

        if ($exchange->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a exchange that is approved.');
        }

        $modelClass = !is_null($request->validated('gdn_id')) ? Gdn::class : Sale::class;

        $exchangeableId = !is_null($request->validated('gdn_id')) ? $request->validated('gdn_id') : $request->validated('sale_id');

        $model = new $modelClass();

        $exchange = DB::transaction(function () use ($request, $model, $exchange, $exchangeableId) {
            $exchange->forceDelete();

            $exchange = $model->exchange()->create($request->safe()->except('exchange'));

            $exchange->exchangeable_id = $exchangeableId;

            $exchange->save();

            $exchange->exchangeDetails()->createMany($request->validated('exchange'));

            $exchange->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve Exchange'), new ExchangeCreated($exchange));

            return $exchange;
        }, 2);

        return redirect()->route('exchanges.show', $exchange->id);
    }

    public function destroy(Exchange $exchange)
    {
        if ($exchange->isExecuted()) {
            return back()->with('failedMessage', 'You can not delete an exchange that is executed.');
        }

        if ($exchange->isapproved()) {
            return back()->with('failedMessage', 'You can not delete an exchange that is approved.');
        }

        $exchange->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
