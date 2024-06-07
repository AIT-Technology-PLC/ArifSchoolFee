<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Services\Models\ArifPayService;
use App\Http\Requests\ArifPayTransactionDetailRequest;

class ArifPayController extends Controller
{
    private $API_key;
    private $HAHU_API_key;
    private $HAHU_DEVICE_id;
    private $expireDate; 
    private $arifPayService;

    public function __construct(ArifPayService $arifPayService) 
    { 
        $this->API_key = config('app.arifpay_api_key'); 
        $this->HAHU_API_key = config('app.hahu_api_key'); 
        $this->HAHU_DEVICE_id = config('app.hahu_device_id'); 
        $this->arifPayService = $arifPayService;
    } 
 
    public function makePayment($id)
    { 
        return $this->arifPayService->makePayment($id, $this->API_key);    
    }

    public function callback(ArifPayTransactionDetailRequest $request)
    {
        return $this->arifPayService->callback($request, $this->HAHU_API_key, $this->HAHU_DEVICE_id);   
    }

    public function successSession($routeId)
    {
        return redirect()->route('gdns.show', $routeId)->with('successMessage', 'The Transaction was made successfully.');  
    }

    public function CanceledSession($routeId)
    {
        return redirect()->route('gdns.show', $routeId)->with('failedMessage', 'The transaction has been canceled.');
    }

    public function ariPayRecipt($transactionId)
    {
        return $this->arifPayService->ariPayRecipt($transactionId);   
    }
}