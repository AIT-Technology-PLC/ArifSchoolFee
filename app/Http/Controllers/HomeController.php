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
        $chartjs = app()->chartjs
            ->name('lineChartTest')
            ->type('line')
            ->size(['width' => 400, 'height' => 300])
            ->labels(['January', 'February', 'March', 'April', 'May', 'June', 'July'])
            ->datasets([
                [
                    "label" => "My First dataset",
                    'backgroundColor' => "rgba(61, 134, 96, 0.31)",
                    'borderColor' => "rgba(61, 134, 96, 0.7)",
                    "pointBorderColor" => "rgba(61, 134, 96, 0.7)",
                    "pointBackgroundColor" => "rgba(61, 134, 96, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                ],
                [
                    "label" => "My Second dataset",
                    'backgroundColor' => "rgba(134, 61, 99, 0.31)",
                    'borderColor' => "rgba(134, 61, 99, 0.7)",
                    "pointBorderColor" => "rgba(134, 61, 99, 0.7)",
                    "pointBackgroundColor" => "rgba(134, 61, 99, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    'data' => [12, 33, 44, 44, 55, 23, 40],
                ],
            ])
            ->options([]);

        $pie = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 450])
            ->labels(['Label x', 'Label y'])
            ->datasets([
                [
                    'backgroundColor' => ['#863d63', '#86843d'],
                    'hoverBackgroundColor' => ['#863d63', '#86843d'],
                    'data' => [50, 50],
                ],
            ])
            ->options([]);

        return view('home', compact('chartjs', 'pie'));
    }
}
