<?php

use App\Http\Controllers\Report as Report;
use Illuminate\Support\Facades\Route;

Route::get('/reports/{customer}/profiles', Report\CustomerProfileReportController::class)->name('reports.profile');
Route::get('/reports/sales', [Report\SaleReportController::class, 'index'])->name('reports.sale');
Route::get('/reports/sales/export', [Report\SaleReportController::class, 'export'])->name('reports.sale_export');
Route::get('/reports/expenses', [Report\ExpenseReportController::class, 'index'])->name('reports.expense');
Route::get('/reports/expenses/export', [Report\ExpenseReportController::class, 'export'])->name('reports.expense_export');
Route::get('/reports/returns', [Report\ReturnReportController::class, 'index'])->name('reports.return');
Route::get('/reports/returns/export', [Report\ReturnReportController::class, 'export'])->name('reports.return_export');
