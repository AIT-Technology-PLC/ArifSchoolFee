<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Models\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sale Management');
    }

    public function assignFSNumber(Request $request, SaleService $saleService)
    {
        if (authUser()->cannot('Assign FS Sale')) {
            return response()->json([
                'message' => 'Not allowed.',
            ]);
        }

        $validator = Validator::make($request->input(), [
            'fs_number' => ['required', 'string'],
            'invoice_number' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ]);
        }

        [$isExecuted, $message] = $saleService->assignFSNumber($validator->validate());

        return response()->json([
            'is_success' => $isExecuted,
            'message' => $message,
        ]);
    }
}
