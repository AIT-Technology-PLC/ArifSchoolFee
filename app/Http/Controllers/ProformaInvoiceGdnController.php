<?php

namespace App\Http\Controllers;

use App\Models\ProformaInvoice;
use Illuminate\Http\Request;

class ProformaInvoiceGdnController extends Controller
{
    public function __invoke(ProformaInvoice $proformaInvoice, Request $request)
    {
        $this->authorize('view', $proformaInvoice);

        $request->merge([
            'customer_id' => $proformaInvoice->customer_id ?? '',
            'gdn' => $proformaInvoice->proformaInvoiceDetails->toArray(),
        ]);

        return redirect()->route('gdns.create')->withInput($request->all());
    }
}
