<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleGdnController extends Controller
{
    public function create(Sale $sale, Request $request)
    {
        $this->authorize('view', $sale);

        $request->merge([
            'sale_id' => $sale->id,
            'customer_id' => $sale->customer_id,
            'payment_type' => $sale->payment_type,
            'gdn' => $sale->saleDetails->toArray(),
        ]);

        return redirect()->route('gdns.create')->withInput($request->all());
    }
}
