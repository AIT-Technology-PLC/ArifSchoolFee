<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Reports\StaffReport;

class StaffByBranchChart
{
    protected $chart, $staffReport;

    public function __construct(StaffReport $staffReport)
    {
        $this->chart = new LarapexChart();

        $this->staffReport = $staffReport;
    } 
   
    public function build()
    {
        $branchData = $this->staffReport->getStaffByBranch();

        if ($branchData->isEmpty()) {
            $totals = [1];
            $labels = ['No Data'];
            $colors = ['#D3D3D3'];
        }else {
            $totals = $branchData->pluck('total')->toArray();
            $labels = $branchData->pluck('branch_name')->toArray();
            $colors = ['#6079ca', '#3FB3E8','#C99289','#864f50','#86843d'];
        }

        return $this->chart->pieChart()
            ->setTitle('Branch Distribution')
            ->setSubtitle($branchData->isEmpty() ? 'No data available' : 'Staff by branch')
            ->addData($totals)
            ->setLabels($labels)
            ->setColors($colors);
    }
}