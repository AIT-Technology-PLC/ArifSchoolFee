<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Reports\StudentReport;

class StudentsByBranchChart
{
    protected $chart, $studentReport;

    public function __construct(StudentReport $studentReport)
    {
        $this->chart = new LarapexChart();

        $this->studentReport = $studentReport;
    } 
   
    public function build()
    {
        $branchData = $this->studentReport->getStudentsByBranch();

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
            ->setSubtitle($branchData->isEmpty() ? 'No data available' : 'Students by branch')
            ->addData($totals)
            ->setLabels($labels)
            ->setColors($colors);
    }
}