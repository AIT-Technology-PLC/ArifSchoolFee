<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierDebtSettlementRequest;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\SupplierImport;
use App\Models\Supplier;
use App\Services\Models\SupplierService;

class SupplierController extends Controller
{
    private $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->middleware('isFeatureAccessible:Supplier Management');

        $this->middleware('isFeatureAccessible:Debt Management')->only('settle', 'settleDebt');

        $this->supplierService = $supplierService;
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Supplier::class);

        ini_set('max_execution_time', '-1');

        (new SupplierImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }

    public function settle(Supplier $supplier)
    {
        $this->authorize('settle', $supplier->debts()->first());

        return view('suppliers.settle', compact('supplier'));
    }

    public function settleDebt(StoreSupplierDebtSettlementRequest $request, Supplier $supplier)
    {
        $this->authorize('settle', $supplier->debts()->first());

        [$isExecuted, $message] = $this->supplierService->settleDebt($request->validated(), $supplier);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('suppliers.debts.index', $supplier->id);
    }
}
