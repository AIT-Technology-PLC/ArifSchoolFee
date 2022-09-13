<?php

use App\Http\Controllers\Report as Report;
use Illuminate\Support\Facades\Route;

Route::get('/reports/sales-performance', Report\SalesPerformanceController::class)->name('reports.sales_performance');

Route::get('/reports/expense', Report\ExpenseReportController::class)->name('reports.expense');