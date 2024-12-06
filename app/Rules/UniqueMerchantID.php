<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueMerchantID implements Rule
{
    private $companyId;
    private $paymentMethodId;
    private $merchantId;
    private $routeID;

    public function __construct($companyId, $paymentMethodId, $merchantId, $routeID = null)
    {
        $this->companyId = $companyId;
        $this->paymentMethodId = $paymentMethodId;
        $this->merchantId = $merchantId;
        $this->routeID = $routeID;
    }

    public function passes($attribute, $value)
    {  
        if (DB::table('payment_gateways')->where('id', $this->routeID)->where('company_id', $this->companyId)->where('payment_method_id', $this->paymentMethodId)->where('merchant_id', $this->merchantId)->exists()) {
            return true;
        }

        if ($this->routeID === null) {
            $existsForCompanyPayment = DB::table('payment_gateways')
                ->where('company_id', $this->companyId)
                ->where('payment_method_id', $this->paymentMethodId)
                ->exists();
        } else {
            $existsForCompanyPayment = DB::table('payment_gateways')
                ->where('company_id', $this->companyId)
                ->where('payment_method_id', $this->paymentMethodId)
                ->where('id', '!=', $this->routeID)
                ->exists();
        }

        if ($existsForCompanyPayment) {
            return false;
        }

        $exists = DB::table('payment_gateways')
            ->where('company_id', $this->companyId)
            ->where('payment_method_id', $this->paymentMethodId)
            ->where('merchant_id', $this->merchantId)
            ->exists();

        return !$exists;
    }

    public function message()
    {
        return 'The Merchant Id has already been stored for this School and payment method combination.';
    }
}
