<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PriceIncrementDatatable;
use App\DataTables\PriceIncrementDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriceIncrementRequest;
use App\Http\Requests\UpdatePriceIncrementRequest;
use App\Models\Price;
use App\Models\PriceIncrement;
use App\Notifications\PriceIncrementCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PriceIncrementController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Price Increment');

        $this->authorizeResource(PriceIncrement::class);
    }

    public function index(PriceIncrementDatatable $datatable)
    {
        $datatable->builder()->setTableId('price-increments-datatable')->orderBy(1, 'asc');

        $totalPriceIncrements = PriceIncrement::count();

        $totalApproved = PriceIncrement::approved()->count();

        $totalNotApproved = $totalPriceIncrements - $totalApproved;

        return $datatable->render('price-increments.index', compact('totalPriceIncrements', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $currentPriceIncrementCode = nextReferenceNumber('price_increments');

        $products = Price::get(['product_id']);

        return view('price-increments.create', compact('currentPriceIncrementCode', 'products'));
    }

    public function store(StorePriceIncrementRequest $request)
    {
        $priceIncrement = DB::transaction(function () use ($request) {
            $priceIncrement = PriceIncrement::create($request->validated());

            if ($request->validated(['target_product']) == "Upload Excel") {
                return $priceIncrement;
            }

            if ($request->validated(['target_product']) == "All Products") {
                $products = Price::all();
                foreach ($products as $product) {
                    $productId['product_id'] = $product->product_id;
                    $priceIncrement->priceIncrementDetails()->create($productId);
                }

                return $priceIncrement;
            }

            foreach ($request->validated(['product_id']) as $incrementDetail) {
                $product['product_id'] = $incrementDetail;

                $priceIncrement->priceIncrementDetails()->create($product);
            }

            Notification::send(Notifiables::byNextActionPermission('Approve Price Increment'), new PriceIncrementCreated($priceIncrement));

            return $priceIncrement;
        });

        return redirect()->route('price-increments.show', $priceIncrement->id);
    }

    public function show(PriceIncrement $priceIncrement, PriceIncrementDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('price-increment-details-datatable');

        $priceIncrement->load(['priceIncrementDetails']);

        return $datatable->render('price-increments.show', compact('priceIncrement'));
    }

    public function edit(PriceIncrement $priceIncrement)
    {
        if ($priceIncrement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a price increment that is approved.');
        }

        $products = Price::get(['product_id']);

        $priceIncrement->load(['priceIncrementDetails']);

        return view('price-increments.edit', compact('priceIncrement', 'products'));
    }

    public function update(UpdatePriceIncrementRequest $request, PriceIncrement $priceIncrement)
    {
        if ($priceIncrement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a price increment that is approved.');
        }

        DB::transaction(function () use ($request, $priceIncrement) {
            $priceIncrement->update($request->validated());

            $priceIncrement->priceIncrementDetails()->forceDelete();

            if ($request->validated(['target_product']) == "Upload Excel") {
                return $priceIncrement;
            }

            if ($request->validated(['target_product']) == "All Products") {
                $products = Price::all();
                foreach ($products as $product) {
                    $productId['product_id'] = $product->product_id;
                    $priceIncrement->priceIncrementDetails()->create($productId);
                }

                return $priceIncrement;
            }

            foreach ($request->validated(['product_id']) as $incrementDetail) {
                $product['product_id'] = $incrementDetail;

                $priceIncrement->priceIncrementDetails()->create($product);
            }

            return $priceIncrement;
        });

        return redirect()->route('price-increments.show', $priceIncrement->id);
    }

    public function destroy(PriceIncrement $priceIncrement)
    {
        if ($priceIncrement->isApproved()) {
            return back()->with('failedMessage', 'You can not delete a price increment that is approved.');
        }

        $priceIncrement->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}