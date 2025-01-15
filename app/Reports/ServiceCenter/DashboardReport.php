<?php

namespace App\Reports\ServiceCenter;

use App\Models\AssignFeeMaster;
use App\Models\Company;
use App\Models\FeePayment;
use App\Models\PaymentTransaction;

class DashboardReport
{
    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getTotalSchools()
    {
        return Company::enabled()->count();
    }

    public function getAssignedFeeMasterThisMonth()
    {
        return AssignFeeMaster::whereHas('feeMaster', function ($query) {
            $query->whereMonth('due_date', now()->month)
                  ->whereYear('due_date', now()->year);
        })->count();
    }

    public function getThisMonthEstimation()
    {
        return AssignFeeMaster::with('feeMaster')
                ->whereHas('feeMaster', function ($query) { 
                        $query->whereMonth('due_date', now()->month)->whereYear('due_date', now()->year); 
                    })
                ->get()
                ->sum(function ($assignFeeMaster) {
                    return $assignFeeMaster->feeMaster ? $assignFeeMaster->feeMaster->amount : 0;
            });
    }

    public function getOverduePaymentCount()
    {
        return AssignFeeMaster::with('feeMaster')
                ->whereHas('feeMaster', function ($query) {
                    $query->whereDate('due_date', '<', now());  // due_date is in the past
                })
                ->doesntHave('feePayments')
                ->count();
    }

    public function getSchoolsServedByUserThisMonth()
    {
        return FeePayment::where('created_by', authUser()->id)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->with('company:id,name,currency')
            ->get()
            ->groupBy('company_id')
            ->map(function ($payments, $companyId) {
                $company = $payments->first()->company;
                return [
                    'company_id' => $companyId,
                    'currency' => $company ? $company->currency : '',
                    'company_name' => $company ? $company->name : 'Unknown',
                    'total_payments' => $payments->sum('amount'),
                ];
            })
            ->values();
    }

    public function getCompletedTransactionByUserThisMonth()
    {
        return FeePayment::where('created_by', authUser()->id)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->count();
    }

    public function getPendingTransactionsThisMonth()
    {
        return PaymentTransaction::where('status', 'pending')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }
}
