<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Services\Models\ArifPayService;
use App\Http\Requests\ArifPayTransactionDetailRequest;
use Illuminate\Http\Request;

class ArifPayController extends Controller
{
    // private $API_key;
    // private $arifPayService;

    // public function __construct(ArifPayService $arifPayService) 
    // { 
    //     $this->API_key = config('app.arifpay_api_key'); 
    // } 

    // public function callback(ArifPayTransactionDetailRequest $request)
    // {
    //     return $this->arifPayService->callback($request);   
    // }

    public function successSession($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('successMessage', 'The Transaction was made successfully.');  
    }

    public function cancelSession($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('failedMessage', 'The transaction has been canceled.');
    }

    public function errorSession($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('failedMessage', 'There was an error processing the payment.');
    }
}