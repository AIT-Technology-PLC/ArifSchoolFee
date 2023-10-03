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
Route::get('/reports/inventory-transfer', [Report\InventoryTransferReportController::class,'index'])->name('reports.inventory_transfer');
Route::get('/reports/credits', [Report\CreditReportController::class,'index'])->name('reports.credit');
Route::get('/reports/inventory-summary', [Report\InventorySummaryReportController::class,'index'])->name('reports.inventory_summary');
Route::get('/reports/inventory-valuation', [Report\InventoryValuationReportController::class,'index'])->name('reports.inventory_valuation');
Route::get('/reports/profit', [Report\ProfitReportController::class, 'index'])->name('reports.profit');
Route::get('/reports/batches', [Report\InventoryBatchReportController::class, 'index'])->name('reports.inventory_batch');
Route::get('/reports/sale-by-payment', [Report\SaleByPaymentReportController::class, 'index'])->name('reports.sale_by_payment');

