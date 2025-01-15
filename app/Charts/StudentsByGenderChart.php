<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Reports\StudentReport;

class StudentsByGenderChart
{
    protected $chart, $studentReport;

    public function __construct(StudentReport $studentReport)
    {
        $this->chart = new LarapexChart();

        $this->studentReport = $studentReport;
    } 
   
    public function build()
    {
        $genderData = $this->studentReport->getStudentsByGender();

        if ($genderData->isEmpty()) {
            $totals = [1];
            $labels = ['No Data'];
            $colors = ['#D3D3D3'];
        }else {
            $totals = $genderData->pluck('total')->toArray();
            $labels = $genderData->pluck('gender')->map(fn($gender) => ucfirst($gender))->toArray();
            $colors = ['#3FB3E8', '#C99289'];
        }

        return $this->chart->donutChart()
            ->setTitle('Gender Distribution')
            ->setSubtitle($genderData->isEmpty() ? 'No data available' : 'Students by gender')
            ->addData($totals)
            ->setLabels($labels)
            ->setColors($colors);
    }
}