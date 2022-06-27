<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\GdnImport;
use App\Models\Credit;
use App\Models\Gdn;
use App\Models\Siv;
use App\Notifications\GdnApproved;
use App\Notifications\GdnPrepared;
use App\Notifications\GdnSubtracted;
use App\Rules\CheckCustomerCreditLimit;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidatePrice;
use App\Rules\VerifyCashReceivedAmountIsValid;
use App\Services\Models\GdnService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GdnController extends Controller
{
    private $gdnService;

    public function __construct(GdnService $gdnService)
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->middleware('isFeatureAccessible:Credit Management')->only('convertToCredit');

        $this->middleware('isFeatureAccessible:Siv Management')->only('convertToSiv');

        $this->gdnService = $gdnService;
    }

    public function approve(Gdn $gdn, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $gdn);

        [$isExecuted, $message] = $action->execute($gdn, GdnApproved::class, 'Subtract GDN');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        if (!$gdn->isApproved()) {
            return back()->with('failedMessage', 'This Delivery Order is not approved yet.');
        }

        $gdn->load(['gdnDetails.product', 'customer', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        return Pdf::loadView('gdns.print', compact('gdn'))->stream();
    }

    public function convertToSiv(Gdn $gdn)
    {
        $this->authorize('create', Siv::class);

        [$isExecuted, $message, $siv] = $this->gdnService->convertToSiv($gdn, auth()->user());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sivs.show', $siv->id);
    }

    public function subtract(Gdn $gdn)
    {
        $this->authorize('subtract', $gdn);

        [$isExecuted, $message] = $this->gdnService->subtract($gdn, auth()->user());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read GDN', $gdn->gdnDetails->pluck('warehouse_id'), $gdn->createdBy),
            new GdnSubtracted($gdn)
        );

        return back();
    }

    public function close(Gdn $gdn)
    {
        $this->authorize('close', $gdn);

        [$isExecuted, $message] = $this->gdnService->close($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', 'Delivery Order closed and archived successfully.');
    }

    public function convertToCredit(Gdn $gdn)
    {
        $this->authorize('convertToCredit', $gdn);

        [$isExecuted, $message] = $this->gdnService->convertToCredit($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('credits.show', $gdn->credit->id);
    }

    public function import(UploadImportFileRequest $importFileRequest)
    {
        $this->authorize('import', Gdn::class);

        ini_set('max_execution_time', '-1');

        $sheets = (new GdnImport)->toArray($importFileRequest->safe()['file']);
        $data = $sheets['master'][0];
        $data['gdn'] = $sheets['detail'];
        $data['code'] = nextReferenceNumber('gdns');

        $validatedData = $this->validatedData($data);

        $gdn = DB::transaction(function () use ($validatedData) {
            $gdn = Gdn::create(Arr::except($validatedData, 'gdn'));

            $gdn->gdnDetails()->createMany($validatedData['gdn']);

            Notification::send(Notifiables::byNextActionPermission('Approve GDN'), new GdnPrepared($gdn));

            return $gdn;
        });

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function validatedData($data)
    {
        return Validator::make($data, [
            'code' => ['required', 'integer', new UniqueReferenceNum('gdns')],
            'gdn' => ['required', 'array'],
            'gdn.*.product_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('products')],
            'gdn.*.warehouse_id' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('sales')->pluck('id'))],
            'gdn.*.unit_price' => ['nullable', 'numeric', new ValidatePrice(['gdn' => $data['gdn']])],
            'gdn.*.quantity' => ['required', 'numeric', 'gt:0'],
            'gdn.*.description' => ['nullable', 'string'],
            'gdn.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'), new CheckCustomerCreditLimit($data['discount'],
                $data['gdn'],
                $data['payment_type'],
                $data['cash_received_type'],
                $data['cash_received']),
            ],
            'sale_id' => ['nullable', 'integer', new MustBelongToCompany('sales')],
            'issued_on' => ['required', 'date'],
            'payment_type' => ['required', 'string', function ($attribute, $value, $fail) use ($data) {
                if ($value == 'Credit Payment' && is_null($data['customer_id'])) {
                    $fail('Creating a credit for delivery order that has no customer is not allowed.');
                }
            }],

            'cash_received_type' => ['required', 'string', function ($attribute, $value, $fail) use ($data) {
                if ($data['payment_type'] == 'Cash Payment' && $value != 'percent') {
                    $fail('When payment type is "Cash Payment", the type should be "Percent".');
                }
            }],

            'description' => ['nullable', 'string'],

            'cash_received' => ['required', 'numeric', 'gte:0', new VerifyCashReceivedAmountIsValid($data['discount'], $data['gdn'], $data['cash_received_type']), function ($attribute, $value, $fail) use ($data) {
                if ($data['cash_received_type'] == 'percent' && $value > 100) {
                    $fail('When type is "Percent", the percentage amount must be between 0 and 100.');
                }
                if ($data['payment_type'] == 'Cash Payment' && $value != 100) {
                    $fail('When payment type is "Cash Payment", the percentage amount must be 100.');
                }
            }],

            'due_date' => ['nullable', 'date', 'after:issued_on', 'required_if:payment_type,Credit Payment', 'prohibited_if:payment_type,Cash Payment'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ])->validated();
    }
}