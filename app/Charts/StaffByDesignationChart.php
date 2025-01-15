<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Reports\StaffReport;

class StaffByDesignationChart
{
    protected $chart, $staffReport;

    public function __construct(StaffReport $staffReport)
    {
        $this->chart = new LarapexChart();

        $this->staffReport = $staffReport;
    } 
   
    public function build()
    {
        $designationData = $this->staffReport->getStaffByDesignation();

        if ($designationData->isEmpty()) {
            $totals = [1];
            $labels = ['No Data'];
            $colors = ['#D3D3D3'];
        }else {
            $totals = $designationData->pluck('total')->toArray();
            $labels = $designationData->pluck('designation_name')->toArray();
            $colors = ['#6079ca', '#3FB3E8','#C99289','#864f50','#86843d'];
        }

        return $this->chart->donutChart()
            ->setTitle('Designation Distribution')
            ->setSubtitle($designationData->isEmpty() ? 'No data available' : 'Staff by designation')
            ->addData($totals)
            ->setLabels($labels)
            ->setColors($colors);
    }
}