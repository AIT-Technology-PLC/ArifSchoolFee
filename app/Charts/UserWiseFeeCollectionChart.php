<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class UserWiseFeeCollectionChart
{
    protected $chart, $dashboardReport;

    public function __construct($dashboardReport)
    {
        $this->chart = new LarapexChart();

        $this->dashboardReport = $dashboardReport;
    } 
   
    public function build()
    {
        $schools = collect($this->dashboardReport->getSchoolsServedByUserThisMonth());

        return $this->chart->pieChart()
            ->setTitle('Schools Served by User')
            ->setSubtitle('Current Month')
            ->addData($schools->pluck('total_payments')->toArray())
            ->setLabels(
                $schools->map(function ($school) {
                    return "{$school['company_name']} ({$school['currency']})";
                })->toArray()
            );
        }
}