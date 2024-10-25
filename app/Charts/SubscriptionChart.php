<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class SubscriptionChart
{
    protected $chart, $subscriptionReport;

    public function __construct($subscriptionReport)
    {
        $this->chart = new LarapexChart();

        $this->subscriptionReport = $subscriptionReport;
    } 
   
    public function build()
    {
        $companies = $this->subscriptionReport->getSubscriptionsThisAndNextMonth();

        $daysLeft = [];

        foreach ($companies as $company) {
            $daysLeft[] = today()->diffInDays($company->subscription_expires_on, false);
        }

        return $this->chart->pieChart()
            ->setTitle('Subscription')
            ->setSubtitle('Expiry Days Left')
            ->addData($daysLeft)
            ->setLabels($companies->pluck('name')->toArray())
            ->setColors(['#0736a1', '#cccccc']);
        }
}