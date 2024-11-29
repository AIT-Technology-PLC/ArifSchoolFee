<?php

namespace App\Reports;

use App\Models\AssignFeeMaster;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Warehouse;

class DashboardReport
{
    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    public function getTotalStudents()
    {
        return Student::count();
    }

    public function getTotalStaff()
    {
        return Staff::count();
    }

    public function getActiveBranches()
    {
        return Warehouse::active()->count();
    }

    public function getAssignedFeeMasterThisMonth()
    {
        return AssignFeeMaster::count();
    }

    public function getThisMonthEstimation()
    {
        return AssignFeeMaster::with('feeMaster')
                ->whereHas('feeMaster', function ($query) { $query->whereMonth('created_at', now()->month); })
                ->get()
                ->sum(function ($assignFeeMaster) {
                    return $assignFeeMaster->feeMaster ? $assignFeeMaster->feeMaster->amount : 0;
            });
    }

    public function getThisMonthCollectedAmount()
    {
        return AssignFeeMaster::with('feePayments')
            ->whereHas('feeMaster', function ($query) {
                $query->whereMonth('created_at', now()->month);
            })
            ->get()
            ->sum(function ($assignFeeMaster) {
                return $assignFeeMaster->feePayments->sum('amount');
            });
    }

    public function getThisMonthVATAmount()
    {
        $collectedAmount = $this->getThisMonthCollectedAmount();
        $vatRate = 0.15;
        
        return $collectedAmount * $vatRate;
    }

    public function getMonthlyCollectedAmount()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $dailyCollectedAmounts = [];

        foreach (new \DatePeriod($startOfMonth, \DateInterval::createFromDateString('5 day'), $endOfMonth) as $date) {
            $collectedAmount = AssignFeeMaster::with('feePayments')
                ->whereHas('feeMaster', function ($query) use ($date) {
                    $query->whereYear('created_at', $date->format('Y'))
                    ->whereMonth('created_at', $date->format('m'))
                    ->whereDay('created_at', $date->format('d'));
                })
                ->get()
                ->sum(function ($assignFeeMaster) {
                    return $assignFeeMaster->feePayments->sum('amount');
                });

            $dailyCollectedAmounts[$date->format('d')] = $collectedAmount;
        }

        return $dailyCollectedAmounts;
    }
}
