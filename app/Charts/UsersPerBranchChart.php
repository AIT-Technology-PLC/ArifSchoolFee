<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class UsersPerBranchChart
{
    protected $chart, $engagementReport;

    public function __construct($engagementReport)
    {
        $this->chart = new LarapexChart();

        $this->engagementReport = $engagementReport;
    } 
   
    public function build()
    {
        $branches = collect($this->engagementReport->getBranchesWithUserCount);

        return $this->chart->barChart()
            ->setTitle('Branches User Count')
            ->setSubtitle('User Engagement per Branch')
            ->addData('User', $branches->pluck('original_users_count')->toArray())
            ->setXAxis($branches->pluck('name')->toArray())
            ->setGrid();
        }
}