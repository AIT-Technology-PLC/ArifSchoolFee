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

        if (empty($dailyCollectedAmounts['collected']) && empty($dailyCollectedAmounts['estimated'])) {
            return $this->chart->areaChart()
                ->setTitle('Collected Fees for ' . now()->format('F Y'))
                ->setSubtitle('No data available')
                ->addData('Estimated Fees', [0]) // Default value (0) to indicate no data
                ->addData('Collected Fees', [0]) // Default value (0) to indicate no data
                ->setXAxis(['No Data Available']) // Placeholder for x-axis
                ->setColors(['#D3D3D3']) // Neutral color indicating no data
                ->setGrid();
        }

        return $this->chart->areaChart()
            ->setTitle('Collected Fees for ' . now()->format('F Y'))
            ->setSubtitle('Total collected fees')
            ->addData('Estimated Fees', array_values($dailyCollectedAmounts['estimated']))
            ->addData('Collected Fees', array_values($dailyCollectedAmounts['collected']))
            ->setXAxis(array_keys($dailyCollectedAmounts['weeks']))
            ->setColors(['#3FB3E8', '#C99289'])
            ->setGrid();
    }
}