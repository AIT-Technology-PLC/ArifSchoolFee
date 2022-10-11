<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\PriceIncrementImport;
use App\Models\PriceIncrement;
use App\Notifications\PriceIncrementApproved;
use App\Services\Models\PriceIncrementService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PriceIncrementController extends Controller
{
    private $priceIncrementService;

    public function __construct(PriceIncrementService $priceIncrementService)
    {
        $this->middleware('isFeatureAccessible:Price Increment');

        $this->priceIncrementService = $priceIncrementService;
    }
    public function approve(PriceIncrement $priceIncrement)
    {
        $this->authorize('approve', $priceIncrement);

        [$isExecuted, $message] = $this->priceIncrementService->approve($priceIncrement);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(Notifiables::byNextActionPermission('Read Price Increment'), new PriceIncrementApproved($priceIncrement));

        return back()->with('successMessage', $message);
    }

    public function import(UploadImportFileRequest $request, PriceIncrement $priceIncrement)
    {
        $this->authorize('import', PriceIncrement::class);

        ini_set('max_execution_time', '-1');

        DB::transaction(function () use ($request, $priceIncrement) {
            (new PriceIncrementImport($priceIncrement))->import($request->validated('file'));
        });

        return back()->with('imported', __('messages.file_imported'));
    }
}