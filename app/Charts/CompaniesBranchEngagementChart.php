<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CompaniesBranchEngagementChart
{
    protected $chart, $engagementReport;

    public function __construct($engagementReport)
    {
        $this->chart = new LarapexChart();

        $this->engagementReport = $engagementReport;
    } 
   
    public function build()
    {
        $branches = collect($this->engagementReport->branches);

        return $this->chart->barChart()
            ->setTitle('Branches')
            ->setSubtitle('Engagement Metric')
            ->addData('Branch', $branches->values()->toArray())
            ->setXAxis($branches->keys()->toArray())
            ->setGrid();
    }
}