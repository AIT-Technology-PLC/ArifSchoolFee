<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertGdnToSivRequest;
use App\Http\Requests\StoreGdnRequest;
use App\Http\Requests\UploadImportFileRequest;
use App\Models\Gdn;
use App\Models\Siv;
use App\Notifications\GdnSubtracted;
use App\Services\Models\GdnService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

use Arifpay\Arifpay\Arifpay;
use Arifpay\Arifpay\Helper\ArifpaySupport;
use Arifpay\Arifpay\Lib\ArifpayBeneficary;
use Arifpay\Arifpay\Lib\ArifpayCheckoutItem;
use Arifpay\Arifpay\Lib\ArifpayCheckoutRequest;
use Arifpay\Arifpay\Lib\ArifpayOptions;
use Illuminate\Support\Carbon;
// use App\Http\Http;

class GdnController extends Controller
{
    private $gdnService;
    private $API_key; 
    private $expireDate; 
    private $requiredFields = [ 
        'cancelUrl', 
        'successUrl', 
        'errorUrl', 
        'notifyUrl', 
        'paymentMethods', 
        'items', 
        'phone', 
    ]; 

    public function __construct(GdnService $gdnService,$API_key  = 'mTuVJ5q61U3UZdBgmxFfmfyw8vvKGrXc', $expireDate = '2025-02-01T03:45:27')
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->middleware('isFeatureAccessible:Credit Management')->only('convertToCredit');

        $this->middleware('isFeatureAccessible:Siv Management')->only('convertToSiv');

        $this->gdnService = $gdnService;

        $this->API_key = $API_key; 

        $this->expireDate = $expireDate; 
    }

    public function calculateBeneficiariesAmount($items) 
    { 
        // dd($items);

        return collect($items)->reduce(function ($total, $item) { 
            $item['quantity'] = 100; 
            $item['price'] = 20; 
            return $total + $item['quantity'] * $item['price']; 
        }, 0); 
    } 

    public function validatePaymentInfo($payment_info) 
    { 
        // dd($payment_info);

        $missingFields = array_filter($this->requiredFields, function ($field) use ($payment_info) { 
            return !array_key_exists($field, $payment_info); 
        }); 
 

        // if (!empty($missingFields)) { 
        //     throw new \Exception('The following required fields are missing from payment_info: ' . implode(', ', $missingFields)); 
        // } 
 
        // dd($payment_info);

        if (!array_key_exists('beneficiaries', $payment_info)) { 
            // $beneficiariesAmount = $this->calculateBeneficiariesAmount($payment_info['items']); 
            $beneficiariesAmount = $this->calculateBeneficiariesAmount($payment_info); 
            $payment_info['beneficiaries'] = [ 
                [ 
                    'accountNumber' => '01320811436100', 
                    'bank' => 'AWINETAA', 
                    'amount' => $beneficiariesAmount, 
                ], 
            ]; 
        } 
    } 

    public function makePayment(Gdn $gdn) 
    { 
        // dd($gdn->gdnDetails->all());
        $client = new Client();

        try {
            $response = $client->post('https://gateway.arifpay.net/api/checkout/session', [
                'headers' => [
                    'x-arifpay-key' => 'mTuVJ5q61U3UZdBgmxFfmfyw8vvKGrXc', // Replace with your actual API key
                    'Content-Type' => 'application/json',
                    'Accepts' => 'application/json',
                ],
                'json' => [
                    "cancelUrl" => "https://example.com",
                    "phone" => "0933624757",
                    "email" => "Ezra@arifpay.com",
                    "errorUrl" => "http://error.com",
                    "notifyUrl" => "https://65cc9048dd519126b83ee6af.mockapi.io/api/notifyUrl",
                    "successUrl" => "http://example.com",
                    "paymentMethods" => ["TELEBIRR_USSD"],
                    "items" => [
                        [
                            "name" => "ሙዝ",
                            "quantity" => 1,
                            "price" => 0.1,
                            "description" => "Fresh Corner premium Banana.",
                            "image" => "https://4.imimg.com/data4/KK/KK/GLADMIN-/product-8789_bananas_golden-500x500.jpg"
                        ]
                    ],
                    "lang" => "EN",
                    "beneficiaries" => [
                        [
                            "accountNumber" => "0132081143610099",
                            "bank" => "AWINETAA",
                            "amount" => 0.1
                        ]
                    ],
                    "nonce" => "32dc81d6-b1-4ba8966999061",
                    "expireDate" => "2024-07-01T03:45:27"
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['data']['paymentUrl'])) {
                $paymentUrl = $data['data']['paymentUrl'];
                return redirect($paymentUrl);
            } else {
                // Handle the case when the payment URL is not found
                return response()->json(['error' => 'Payment URL not found'], 500);
            }

        } catch (\Exception $e) {
            // Handle any exceptions (e.g., connection error, invalid response)
            return response()->json(['error' => 'Error creating checkout session'], 500);
        }       
    }

// public function pay(Gdn $gdn)
    // {
    //     // Instantiate the Arifpay class with your API key
    //     // $arifpay = new Arifpay('mTuVJ5q61U3UZdBgmxFfmfyw8vvKGrXc');
    //     $arifpay = new Arifpay('mTuVJ5q61U3UZdBgmxFfmfyw8vvKGrXc', 'https://gateway.arifpay.org/api');

    //     // Set up the necessary data and parameters for the ArifpayCheckoutRequest object
    //     $d = Carbon::now();
    //     $d->setMonth(10);
    //     $expired = ArifpaySupport::getExpireDateFromDate($d);

    //     $data = new ArifpayCheckoutRequest(
    //         cancel_url: 'https://gateway.arifpay.org/api/sandbox/checkout/session',
    //         error_url: 'https://api.example.com',
    //         notify_url: 'https://gateway.example.com',
    //         expireDate: $expired,
    //         nonce: floor(rand() * 10000) . "",
    //         beneficiaries: [
    //             ArifpayBeneficary::fromJson([
    //                 "accountNumber" => '01320811436100',
    //                 "bank" => 'AWINETAA',
    //                 "amount" => 10.0,
    //             ]),
    //         ],
    //         paymentMethods: ["CARD"],
    //         success_url: 'https://gateway.arifpay.net',
    //         items: [
    //             ArifpayCheckoutItem::fromJson([
    //                 // "price" => $gdn->gdnDetails()->unit_price,
    //                 // "quantity" => $gdn->gdnDetails()->unquantityit_price,
    //                 "name" => 'Bannana',
    //                 "price" => 10.0,
    //                 "quantity" => 1,
    //             ]),
    //         ],
    //     );

    //     // Create the checkout session using the Arifpay instance and the ArifpayCheckoutRequest object
    //     $session = $arifpay->checkout->create($data, new ArifpayOptions(sandbox: true));

    //     // Redirect the user to the payment URL associated with the session
    //     $paymentUrl = $session->paymentUrl;
    //     return redirect($paymentUrl);
    // }

    // public function pay(Gdn $gdn)
    // {
    //     // Instantiate the Arifpay class with your API key
    //     // $arifpay = new Arifpay('mTuVJ5q61U3UZdBgmxFfmfyw8vvKGrXc');
    //     $arifpay = new Arifpay('mTuVJ5q61U3UZdBgmxFfmfyw8vvKGrXc', 'https://gateway.arifpay.net/api/checkout/session');

    //     // Set up the necessary data and parameters for the ArifpayCheckoutRequest object
    //     $d = Carbon::now();
    //     $d->setMonth(10);
    //     $expired = ArifpaySupport::getExpireDateFromDate($d);

    //     $data = new ArifpayCheckoutRequest(
    //         cancel_url: 'https://example.com',
    //         error_url: 'https://example.com',
    //         notify_url: 'https://z7rdw.wiremockapi.cloud/test_notify',
    //         nonce: floor(rand() * 10000) . "",
    //         expireDate: $expired,
            
    //         beneficiaries: [
    //             ArifpayBeneficary::fromJson([
    //                 "accountNumber" => '1000179040747',
    //                 "bank" => 'AWINETAA',
    //                 "amount" => 10.0,
    //             ]),
    //         ],
    //         paymentMethods: ["TELEBIRR"],
    //         success_url: 'https://gateway.arifpay.net',

    //         items: [
    //             ArifpayCheckoutItem::fromJson([
    //                 // "price" => $gdn->gdnDetails()->unit_price,
    //                 // "quantity" => $gdn->gdnDetails()->unquantityit_price,
    //                 "name" => 'Bannana',
    //                 "price" => 10.0,
    //                 "quantity" => 1,
    //             ]),
    //         ],
    //     );

    //     // Create the checkout session using the Arifpay instance and the ArifpayCheckoutRequest object
    //     // $session = $arifpay->checkout->create($data, new ArifpayOptions(sandbox: true));
    //     $session = $arifpay->checkout->create($data, new ArifpayOptions(['sandbox' => true]));

    //     // Redirect the user to the payment URL associated with the session
    //     $paymentUrl = $session->paymentUrl;
    //     return redirect($paymentUrl);
    // }

     // public function pay(Gdn $gdns) 
    // { 
    //     return collect($gdns)->reduce(function ($total, $item) { 
    //         // $item['quantity'] = (int) $item['quantity']; 
    //         // $item['price'] = (float) $item['price']; 
    //         $item['quantity'] = $gdn->gdnDetails()->quantity; 
    //         $item['price'] = $gdn->gdnDetails()->unit_price; 
    //         return $total + $item['quantity'] * $item['price']; 
    //     }, 0); 
    // } 


    public function approve(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        [$isExecuted, $message] = $this->gdnService->approve($gdn);

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

        if ($gdn->isCancelled()) {
            return back()->with('failedMessage', 'This Delivery Order is cancelled.');
        }

        $gdn->load(['gdnDetails.product', 'customer', 'contact', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        $havingBatch = $gdn->gdnDetails()->with('merchandiseBatch')->get()->pluck('merchandiseBatch')->pluck('batch_no')->filter()->isNotEmpty();

        return Pdf::loadView('gdns.print', compact('gdn', 'havingBatch'))->stream();
    }

    public function convertToSiv(Gdn $gdn, ConvertGdnToSivRequest $request)
    {
        $this->authorize('create', Siv::class);

        [$isExecuted, $message, $siv] = $this->gdnService->convertToSiv($gdn, authUser(), $request->validated());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sivs.show', $siv->id);
    }

    public function subtract(Gdn $gdn)
    {
        $this->authorize('subtract', $gdn);

        [$isExecuted, $message] = $this->gdnService->subtract($gdn, authUser());

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

        $formattedData = $this->gdnService->formattedFromExcel($importFileRequest->validated('file'));

        DB::transaction(function () use ($formattedData) {
            foreach ($formattedData as $data) {
                $this->gdnService->import(
                    Validator::make(request()->merge($data)->toArray(), (new StoreGdnRequest)->merge($data)->rules())->validated()
                );
            }
        });

        return back()->with('imported', __('messages.file_imported'));
    }

    public function cancel(Gdn $gdn)
    {
        $this->authorize('cancel', $gdn);

        [$isExecuted, $message] = $this->gdnService->cancel($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function approveAndSubtract(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        $this->authorize('subtract', $gdn);

        [$isExecuted, $message] = $this->gdnService->approveAndSubtract($gdn, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read GDN', $gdn->gdnDetails->pluck('warehouse_id'), $gdn->createdBy),
            new GdnSubtracted($gdn)
        );

        return back();
    }
}