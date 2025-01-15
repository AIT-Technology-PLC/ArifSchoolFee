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
        return AssignFeeMaster::whereHas('feeMaster', function ($query) {
            $query->whereMonth('due_date', now()->month)
                  ->whereYear('due_date', now()->year);
        })->count();
    }

    public function getThisMonthEstimation()
    {
        return AssignFeeMaster::with('feeMaster')
                ->whereHas('feeMaster', function ($query) { 
                        $query->whereMonth('due_date', now()->month)
                              ->whereYear('due_date', now()->year); 
                    })
                ->get()
                ->sum(function ($assignFeeMaster) {
                    return $assignFeeMaster->feeMaster ? $assignFeeMaster->feeMaster->amount : 0;
            });
    }

    public function getThisMonthCollectedAmount()
    {
        return AssignFeeMaster::with('feePayments')
            ->whereHas('feeMaster', function ($query) {
                $query->whereMonth('due_date', now()->month)
                      ->whereYear('due_date', now()->year); 
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
        // Get the start and end dates of the current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Get the estimated total amount to collect for this month
        $estimatedAmount = $this->getThisMonthEstimation();  // Using the getThisMonthEstimation method
        
        // Initialize arrays for weekly collected amounts and estimated amounts
        $weeklyCollectedAmounts = [];
        $weeklyEstimatedAmounts = [];

        // Loop through each week of the month
        $currentWeekStart = $startOfMonth;
        $currentWeekEnd = $startOfMonth->copy()->endOfWeek();

        while ($currentWeekStart <= $endOfMonth) {
            // Collect payments for the current week
            $collectedAmount = AssignFeeMaster::with(['feePayments' => function ($query) use ($currentWeekStart, $currentWeekEnd) {
                $query->whereBetween('payment_date', [$currentWeekStart, $currentWeekEnd]);
            }])
            ->get()
            ->sum(function ($assignFeeMaster) {
                return $assignFeeMaster->feePayments->sum('amount');
            });

            // Store the collected amount for the week
            $weeklyCollectedAmounts[] = $collectedAmount;

            // Store the estimated amount for the week (constant for all weeks)
            $weeklyEstimatedAmounts[] = $estimatedAmount;

            // Move to the next week
            $currentWeekStart = $currentWeekEnd->addDay();
            $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();
        }

        // Return data suitable for the chart (grouped by week)
        return [
            'weeks' => $this->getWeeksOfMonth($startOfMonth, $endOfMonth),  // Week labels (x-axis)
            'collected' => $weeklyCollectedAmounts,  // Collected fees (y-axis)
            'estimated' => $weeklyEstimatedAmounts,  // Estimated fees (y-axis)
        ];
    }

    public function getWeeksOfMonth($startOfMonth, $endOfMonth)
    {
        $weeks = [];
        $currentWeekStart = $startOfMonth;
        $currentWeekEnd = $startOfMonth->copy()->endOfWeek();

        while ($currentWeekStart <= $endOfMonth) {
            $weeks[] = 'Week ' . $currentWeekStart->week;
            $currentWeekStart = $currentWeekEnd->addDay();
            $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();
        }

        return $weeks;
    }
}