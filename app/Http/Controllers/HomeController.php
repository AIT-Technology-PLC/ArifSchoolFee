<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pie = $this->pie();

        $chartjs = $this->chartjs();

        return view('home', compact('chartjs', 'pie'));
    }

    public function chartjs()
    {
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 444, 'height' => 200])
            ->labels(['Raw Materials', 'MRO Items', 'Finished Goods', 'Merchandise'])
            ->datasets([
                [
                    "label" => "Percentage",
                    'backgroundColor' => ['rgba(61, 134, 96, 1)', 'rgba(134, 61, 99, 1)', 'rgba(61, 134, 96, 1)', 'rgba(134, 61, 99, 1)'],
                    'data' => [69, 50, 60, 44],
                ],
            ])
            ->options([
                'legend' => [
                    'display' => false,
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'stacked' => false,
                            'gridLines' => [
                                'display' => false,
                            ],
                        ],
                    ],
                    'yAxes' => [
                        [
                            'stacked' => true,
                            'gridLines' => [
                                'display' => true,
                            ],
                        ],
                    ],
                ],
            ]);

        return $chartjs;
    }

    public function pie()
    {
        $pie = app()->chartjs
            ->name('pieChartTest')
            ->type('doughnut')
            ->size(['width' => 200, 'height' => 200])
            ->labels(['Warehouse 1', 'Warehouse 2'])
            ->datasets([
                [
                    'backgroundColor' => ['rgba(61, 134, 96, 0.9)', 'rgba(134, 61, 99, 0.9)'],
                    'hoverBackgroundColor' => ['rgba(61, 134, 96, 0.9)', 'rgba(134, 61, 99, 0.9)'],
                    'data' => [30, 70],
                ],
            ])
            ->options([]);

        return $pie;
    }
}
