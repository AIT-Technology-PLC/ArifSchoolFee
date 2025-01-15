<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Reports\StaffReport;

class StaffByDepartmentChart
{
    protected $chart, $staffReport;

    public function __construct(StaffReport $staffReport)
    {
        $this->chart = new LarapexChart();

        $this->staffReport = $staffReport;
    } 
   
    public function build()
    {
        $departmentData = $this->staffReport->getStaffByDepartment();

        if ($departmentData->isEmpty()) {
            $totals = [1];
            $labels = ['No Data'];
            $colors = ['#D3D3D3'];
        }else {
            $totals = $departmentData->pluck('total')->toArray();
            $labels = $departmentData->pluck('department_name')->toArray();
            $colors = ['#6079ca', '#3FB3E8','#C99289','#864f50','#86843d'];
        }

        return $this->chart->donutChart()
            ->setTitle('Department Distribution')
            ->setSubtitle($departmentData->isEmpty() ? 'No data available' : 'Staff by department')
            ->addData($totals)
            ->setLabels($labels)
            ->setColors($colors);
    }
}