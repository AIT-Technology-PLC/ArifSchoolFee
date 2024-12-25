<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CollectedFeeChart
{
    protected $chart, $dashboardReport;

    public function __construct($dashboardReport)
    {
        $this->chart = new LarapexChart();

        $this->dashboardReport = $dashboardReport;
    } 
   
    public function build()
    {
        $dailyCollectedAmounts = $this->dashboardReport->getMonthlyCollectedAmount();

        return $this->chart->areaChart()
            ->setTitle('Collected Fees for ' . now()->format('F Y'))
            ->setSubtitle('Total collected fees')
            // ->addData('Collected Fees', array_values($dailyCollectedAmounts))
            ->addData(now()->subMonth()->format('F Y'), [40, 93, 35, 42, 18, 82])
            ->addData(now()->format('F Y'), [70, 29, 77, 28, 55, 45])
            ->setXAxis(array_keys($dailyCollectedAmounts))
            ->setColors(['#3FB3E8', '#C99289'])
            ->setGrid();
    }
}