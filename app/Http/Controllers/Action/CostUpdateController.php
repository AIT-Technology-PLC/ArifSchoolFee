<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\CostUpdateImport;
use App\Models\CostUpdate;
use App\Services\Models\CostUpdateService;
use Illuminate\Support\Facades\DB;

class CostUpdateController extends Controller
{
    private $costUpdateService;

    public function __construct(CostUpdateService $costUpdateService)
    {
        $this->middleware('isFeatureAccessible:Cost Update Management');

        $this->middleware('isFeatureAccessible:Inventory Valuation');

        $this->costUpdateService = $costUpdateService;
    }

    public function approve(CostUpdate $costUpdate)
    {
        $this->authorize('approve', $costUpdate);

        [$isExecuted, $message] = $this->costUpdateService->approve($costUpdate);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function reject(CostUpdate $costUpdate)
    {
        $this->authorize('reject', $costUpdate);

        [$isExecuted, $message] = $this->costUpdateService->reject($costUpdate);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', CostUpdate::class);

        ini_set('max_execution_time', '-1');

        DB::transaction(function () use ($request) {
            $costUpdate = CostUpdate::create([
                'code' => nextReferenceNumber('cost_updates'),
            ]);

            (new CostUpdateImport($costUpdate))->import($request->validated('file'));
        });

        return back()->with('imported', __('messages.file_imported'));
    }
}
