<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class CompaniesPlanChart
{
    protected $chart, $plan;

    public function __construct($plan)
    {
        $this->chart = new LarapexChart();

        $this->plan = $plan;
    } 
   
    public function build()
    {
        $companies = $this->plan->companies;

        return $this->chart->barChart()
            ->setTitle('Comapnies')
            ->setSubtitle('Plan Status')
            ->addData('Status', $companies->pluck('enabled')->toArray())
            ->setXAxis($companies->pluck('name')->toArray())
            ->setGrid();
        }
}