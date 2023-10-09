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

        abort_if($proformaInvoiceDetail->proformaInvoice->isConfirmed(), 403);

        abort_if($proformaInvoiceDetail->proformaInvoice->isCancelled() && !authUser()->can('Delete Cancelled Proforma Invoice'), 403);

        $proformaInvoiceDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
