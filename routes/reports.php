<?php

use App\Http\Controllers\Report as Report;
use Illuminate\Support\Facades\Route;

Route::get('/reports/sales', [Report\SaleReportController::class, 'index'])->name('reports.sale');
Route::get('/reports/sales/export', [Report\SaleReportController::class, 'export'])->name('reports.sale_export');
Route::get('/reports/expenses', Report\ExpenseReportController::class)->name('reports.expense');
Route::get('/reports/returns', Report\ReturnReportController::class)->name('reports.return');
