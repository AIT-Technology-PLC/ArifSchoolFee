<?php

use App\Http\Controllers\Report as Report;
use Illuminate\Support\Facades\Route;

Route::get('/reports/{customer}/customer-profile', Report\CustomerProfileReportController::class)->name('reports.profile');
Route::get('/reports/sales', [Report\SaleReportController::class, 'index'])->name('reports.sale');
Route::get('/reports/sales/export', [Report\SaleReportController::class, 'export'])->name('reports.sale_export');
Route::get('/reports/expenses', [Report\ExpenseReportController::class, 'index'])->name('reports.expense');
Route::get('/reports/expenses/export', [Report\ExpenseReportController::class, 'export'])->name('reports.expense_export');
Route::get('/reports/returns', [Report\ReturnReportController::class, 'index'])->name('reports.return');
Route::get('/reports/returns/export', [Report\ReturnReportController::class, 'export'])->name('reports.return_export');
Route::get('/reports/customers', Report\CustomerReportController::class)->name('reports.customer');
Route::get('/reports/{supplier}/supplier-profile', Report\SupplierProfileReportController::class)->name('reports.supplier_profile');
Route::get('/reports/inventory-level', [Report\InventoryLevelReportController::class,'index'])->name('reports.inventory_level');