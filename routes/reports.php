<?php

use App\Http\Controllers\Report as Report;
use Illuminate\Support\Facades\Route;

Route::name('reports.')
    ->prefix('/reports')
    ->group(function () {
        Route::get('/{customer}/customer-profile', Report\CustomerProfileReportController::class)->name('profile');
        Route::get('/sales', [Report\SaleReportController::class, 'index'])->name('sale');
        Route::get('/sales/print', [Report\SaleReportController::class, 'print'])->name('sale_print');
        Route::get('/sales/export', [Report\SaleReportController::class, 'export'])->name('sale_export');
        Route::get('/expenses', [Report\ExpenseReportController::class, 'index'])->name('expense');
        Route::get('/expenses/export', [Report\ExpenseReportController::class, 'export'])->name('expense_export');
        Route::get('/returns', [Report\ReturnReportController::class, 'index'])->name('return');
        Route::get('/returns/export', [Report\ReturnReportController::class, 'export'])->name('return_export');
        Route::get('/customers', Report\CustomerReportController::class)->name('customer');
        Route::get('/{supplier}/supplier-profile', Report\SupplierProfileReportController::class)->name('supplier_profile');
        Route::get('/inventory-level', [Report\InventoryLevelReportController::class, 'index'])->name('inventory_level');
        Route::get('/inventory-transfer', [Report\InventoryTransferReportController::class, 'index'])->name('inventory_transfer');
        Route::get('/credits', [Report\CreditReportController::class, 'index'])->name('credit');
        Route::get('/inventory-summary', [Report\InventorySummaryReportController::class, 'index'])->name('inventory_summary');
        Route::get('/inventory-valuation', [Report\InventoryValuationReportController::class, 'index'])->name('inventory_valuation');
        Route::get('/profit', [Report\ProfitReportController::class, 'index'])->name('profit');
        Route::get('/profit/print', [Report\ProfitReportController::class, 'printed'])->name('profit_print');
        Route::get('/batches', [Report\InventoryBatchReportController::class, 'index'])->name('inventory_batch');
        Route::get('/sale-by-payment', [Report\SaleByPaymentReportController::class, 'index'])->name('sale_by_payment');
        Route::get('/inventory-in-transit', [Report\InventoryInTransitReportController::class, 'index'])->name('inventory_in_transit');
    });
