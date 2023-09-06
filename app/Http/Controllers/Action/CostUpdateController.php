<?php

namespace App\Http\Controllers\Action;

use App\Actions\RejectTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\CostUpdateImport;
use App\Models\CostUpdate;
use App\Notifications\CostUpdateRejected;
use App\Services\Models\CostUpdateService;
use Illuminate\Support\Facades\DB;

class CostUpdateController extends Controller
{
    private $costUpdateService;

    public function __construct(CostUpdateService $costUpdateService)
    {
        $this->middleware('isFeatureAccessible:Cost Update Management');

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

    public function reject(CostUpdate $costUpdate, RejectTransactionAction $action)
    {
        $this->authorize('reject', $costUpdate);

        if ($costUpdate->isApproved()) {
            return back()->with('failedMessage', 'You can not reject a cost update that is approved.');
        }

        if ($costUpdate->isRejected()) {
            return back()->with('failedMessage', 'This cost update is already rejected.');
        }

        [$isExecuted, $message] = $action->execute($costUpdate, new CostUpdateRejected($costUpdate), 'Read Cost Update');

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
