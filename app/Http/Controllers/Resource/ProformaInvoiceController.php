<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ProformaInvoiceDatatable;
use App\DataTables\ProformaInvoiceDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProformaInvoiceRequest;
use App\Http\Requests\UpdateProformaInvoiceRequest;
use App\Models\MerchandiseBatch;
use App\Models\ProformaInvoice;
use App\Models\ProformaInvoiceDetail;
use App\Notifications\ProformaInvoicePrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ProformaInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Proforma Invoice');

        $this->authorizeResource(ProformaInvoice::class);
    }

    public function index(ProformaInvoiceDatatable $datatable)
    {
        $datatable->builder()->setTableId('proforma-invoices-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalProformaInvoices = ProformaInvoice::count();

        $totalConverted = ProformaInvoice::converted()->count();

        $totalPending = ProformaInvoice::pending()->count();

        $totalCancelled = ProformaInvoice::notPending()->notConverted()->count();

        return $datatable->render('proforma-invoices.index', compact('totalProformaInvoices', 'totalConverted', 'totalPending', 'totalCancelled'));
    }

    public function create()
    {
        $currentProformaInvoiceCode = nextReferenceNumber('proforma_invoices');

        return view('proforma-invoices.create', compact('currentProformaInvoiceCode'));
    }

    public function store(StoreProformaInvoiceRequest $request)
    {
        $proformaInvoice = DB::transaction(function () use ($request) {
            $proformaInvoice = ProformaInvoice::create($request->safe()->except('proformaInvoice'));

            $proformaInvoiceDetails = $proformaInvoice->proformaInvoiceDetails()->createMany($request->validated('proformaInvoice'));

            $deletableDetails = collect();

            foreach ($proformaInvoiceDetails as $proformaInvoiceDetail) {
                if ($proformaInvoiceDetail->product->isBatchable() && is_null($proformaInvoiceDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $proformaInvoiceDetail->product_id)
                        ->when($proformaInvoiceDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$proformaInvoiceDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($proformaInvoiceDetail->id);

                        $proformaInvoice->proformaInvoiceDetails()->create([
                            'product_id' => $proformaInvoiceDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $proformaInvoiceDetail->quantity ? $proformaInvoiceDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $proformaInvoiceDetail->original_unit_price,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $proformaInvoiceDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $proformaInvoiceDetail->quantity - $merchandiseBatch->quantity;
                            $proformaInvoiceDetail->quantity = $difference;
                        }
                    }
                }
            }

            ProformaInvoiceDetail::whereIn('id', $deletableDetails)->forceDelete();

            Notification::send(Notifiables::byNextActionPermission('Convert Proforma Invoice'), new ProformaInvoicePrepared($proformaInvoice));

            return $proformaInvoice;
        });

        return redirect()->route('proforma-invoices.show', $proformaInvoice->id);
    }

    public function show(ProformaInvoice $proformaInvoice, ProformaInvoiceDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('proforma-invoice-details-datatable');

        $proformaInvoice->load(['proformaInvoiceDetails.product', 'proformaInvoiceDetails.merchandiseBatch', 'customer', 'contact']);

        return $datatable->render('proforma-invoices.show', compact('proformaInvoice'));
    }

    public function edit(ProformaInvoice $proformaInvoice)
    {
        if (!$proformaInvoice->isPending()) {
            return back()->with('failedMessage', 'Confirmed or cancelled proforma inovices cannot be edited.');
        }

        $proformaInvoice->load(['proformaInvoiceDetails.product', 'proformaInvoiceDetails.merchandiseBatch', 'customer', 'contact']);

        return view('proforma-invoices.edit', compact('proformaInvoice'));
    }

    public function update(UpdateProformaInvoiceRequest $request, ProformaInvoice $proformaInvoice)
    {
        if (!$proformaInvoice->isPending()) {
            return redirect()->route('proforma-invoices.show', $proformaInvoice->id)
                ->with('failedMessage', 'Confirmed or cancelled proforma inovices cannot be edited.');
        }

        DB::transaction(function () use ($request, $proformaInvoice) {
            $proformaInvoice->update($request->safe()->except('proformaInvoice'));

            $proformaInvoice->proformaInvoiceDetails()->forceDelete();

            $proformaInvoiceDetails = $proformaInvoice->proformaInvoiceDetails()->createMany($request->validated('proformaInvoice'));

            $deletableDetails = collect();

            foreach ($proformaInvoiceDetails as $proformaInvoiceDetail) {
                if ($proformaInvoiceDetail->product->isBatchable() && is_null($proformaInvoiceDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $proformaInvoiceDetail->product_id)
                        ->when($proformaInvoiceDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$proformaInvoiceDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($proformaInvoiceDetail->id);

                        $proformaInvoice->proformaInvoiceDetails()->create([
                            'product_id' => $proformaInvoiceDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $proformaInvoiceDetail->quantity ? $proformaInvoiceDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $proformaInvoiceDetail->original_unit_price,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $proformaInvoiceDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $proformaInvoiceDetail->quantity - $merchandiseBatch->quantity;
                            $proformaInvoiceDetail->quantity = $difference;
                        }
                    }
                }
            }

            ProformaInvoiceDetail::whereIn('id', $deletableDetails)->forceDelete();
        });

        return redirect()->route('proforma-invoices.show', $proformaInvoice->id);
    }

    public function destroy(ProformaInvoice $proformaInvoice)
    {
        abort_if($proformaInvoice->isConverted(), 403);

        abort_if($proformaInvoice->isCancelled() && !authUser()->can('Delete Cancelled Proforma Invoice'), 403);

        $proformaInvoice->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
