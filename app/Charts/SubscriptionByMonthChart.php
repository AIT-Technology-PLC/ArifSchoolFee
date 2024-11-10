<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class SubscriptionByMonthChart
{
    protected $chart, $subscriptionReport;

    public function __construct($subscriptionReport)
    {
        $this->chart = new LarapexChart();

        $this->subscriptionReport = $subscriptionReport;
    } 
   
    public function build()
    {
        $subscriptionCountByMonths = $this->subscriptionReport->getSubscriptionsCountByMonths;

        $subscriptionCounts = [];

        for ($month = 1; $month <= 12; $month++) {
            $subscriptionCounts[] = $subscriptionCountByMonths[$month] ?? 0;
        }

        $months = collect(range(1, 12))->map(function($month) {
            return now()->setMonth($month)->monthName; // Get month names like January, February, etc.
        });

        return $this->chart->barChart()
            ->setTitle('Subscription By Month')
            ->setSubtitle('Monthly Base')
            ->addData('Subscriptions',$subscriptionCounts)
            ->setXAxis($months->toArray())
            ->setGrid();
        }
}