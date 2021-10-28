<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\ProformaInvoiceDetail;

class ProformaInvoiceDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Proforma Invoice');
    }

    public function destroy(ProformaInvoiceDetail $proformaInvoiceDetail)
    {
        $this->authorize('delete', $proformaInvoiceDetail->proformaInvoice);

        if ($proformaInvoiceDetail->proformaInvoice->isConverted()) {
            abort(403);
        }

        if ($proformaInvoiceDetail->proformaInvoice->isCancelled() && !auth()->user()->can('Delete Cancelled Proforma Invoice')) {
            abort(403);
        }

        $proformaInvoiceDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
