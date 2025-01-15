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

        if ($schools->isEmpty()) {
            return $this->chart->pieChart()
                ->setTitle('Schools Served by User')
                ->setSubtitle('Current Month')
                ->addData([1])
                ->setLabels(['No Data Available'])
                ->setColors(['#D3D3D3']); // Neutral color for no data
        }

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