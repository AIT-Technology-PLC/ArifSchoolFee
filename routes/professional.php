<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GdnController;
use App\Http\Controllers\GeneralTenderChecklistController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\MerchandiseInventoryLevelController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TenderChecklistController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\TenderStatusController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/merchandises/level/warehouse/{warehouse}',
    [MerchandiseInventoryLevelController::class, 'getCurrentMerchandiseLevelByWarehouse']);

Route::post('purchase-orders/{purchaseOrder}/close', [PurchaseOrderController::class, 'close'])->name('purchase-orders.close');

Route::resource('suppliers', SupplierController::class);

Route::resource('warehouses', WarehouseController::class);

Route::resource('customers', CustomerController::class);

Route::get('/gdns/{gdn}/print', [GdnController::class, 'printed'])->name('gdns.print');

Route::post('/gdns/{gdn}/approve', [GdnController::class, 'approve'])->name('gdns.approve');

Route::resource('gdns', GdnController::class);

Route::post('/transfers/transfer/{transfer}', [TransferController::class, 'transfer'])->name('transfers.transfer');

Route::post('/transfers/approve/{transfer}', [TransferController::class, 'approve'])->name('transfers.approve');

Route::resource('transfers', TransferController::class);

Route::resource('purchase-orders', PurchaseOrderController::class);

Route::post('/grns/{grn}/approve', [GrnController::class, 'approve'])->name('grns.approve');

Route::resource('grns', GrnController::class);

Route::resource('general-tender-checklists', GeneralTenderChecklistController::class);

Route::resource('tender-statuses', TenderStatusController::class);

Route::resource('tenders', TenderController::class);

Route::resource('tender-checklists', TenderChecklistController::class);
