<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GdnController;
use App\Http\Controllers\GeneralTenderChecklistController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\MerchandiseLevelByWarehouseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TenderChecklistController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\TenderStatusController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/gdns/{gdn}/print', [GdnController::class, 'printed'])->name('gdns.print');

Route::post('purchase-orders/{purchaseOrder}/close', [PurchaseOrderController::class, 'close'])->name('purchase-orders.close');

Route::post('/gdns/{gdn}/approve', [GdnController::class, 'approve'])->name('gdns.approve');

Route::post('/transfers/transfer/{transfer}', [TransferController::class, 'transfer'])->name('transfers.transfer');

Route::post('/transfers/approve/{transfer}', [TransferController::class, 'approve'])->name('transfers.approve');

Route::post('/grns/{grn}/approve', [GrnController::class, 'approve'])->name('grns.approve');

Route::resource('warehouses.merchandises', MerchandiseLevelByWarehouseController::class);

Route::resource('suppliers', SupplierController::class);

Route::resource('warehouses', WarehouseController::class);

Route::resource('customers', CustomerController::class);

Route::resource('gdns', GdnController::class);

Route::resource('transfers', TransferController::class);

Route::resource('purchase-orders', PurchaseOrderController::class);

Route::resource('grns', GrnController::class);

Route::resource('general-tender-checklists', GeneralTenderChecklistController::class);

Route::resource('tender-statuses', TenderStatusController::class);

Route::resource('tenders', TenderController::class);

Route::resource('tender-checklists', TenderChecklistController::class);
