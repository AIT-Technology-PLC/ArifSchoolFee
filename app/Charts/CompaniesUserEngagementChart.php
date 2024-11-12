<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CompaniesUserEngagementChart
{
    protected $chart, $engagementReport;

    public function __construct($engagementReport)
    {
        $this->chart = new LarapexChart();

        $this->engagementReport = $engagementReport;
    } 
   
    public function build()
    {
        $users = collect($this->engagementReport->users);

        return $this->chart->donutChart()
            ->setTitle('Users')
            ->setSubtitle('Engagement Metric')
            ->addData($users->values()->toArray())
            ->setLabels($users->keys()->toArray());
        }
}