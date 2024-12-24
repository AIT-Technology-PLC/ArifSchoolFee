<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function calculateCommission(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'company_id' => 'required|exists:companies,id',
        ]);

        $amount = $request->input('amount');
        $companyId = $request->input('company_id');

        $commission = calculateCommission($amount, $companyId);
        $isCommissionFromPayer = isCommissionFromPayer($request->company_id);

        return response()->json([
            'commission' => $commission,
            'is_commission_from_payer' => $isCommissionFromPayer
        ]);
    }
}
