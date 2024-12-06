<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CompaniesChart
{
    protected $chart, $engagementReport;

    public function __construct($engagementReport)
    {
        $this->chart = new LarapexChart();

        $this->engagementReport = $engagementReport;
    } 
   
    public function build()
    {
        $data = $this->engagementReport->companies();
        $companies = $data['companies'];

        return $this->chart->areaChart()
            ->setTitle('Schools')
            ->setSubtitle('User and Branch Data')
            ->addData('Users', $companies->pluck('employees_count')->toArray())
            ->addData('Branches', $companies->pluck('warehouses_count')->toArray())
            ->setXAxis($companies->pluck('name')->toArray())
            ->setColors(['#3FB3E8', '#C99289'])
            ->setGrid();
    }
}