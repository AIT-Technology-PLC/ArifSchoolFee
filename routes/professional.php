<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GdnController;
use App\Http\Controllers\GeneralTenderChecklistController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\MerchandiseLevelByWarehouseController;
use App\Http\Controllers\PurchaseGrnController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SaleGdnController;
use App\Http\Controllers\SivController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TenderChecklistController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\TenderStatusController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/sale/{sale}/gdn/create', [SaleGdnController::class, 'create'])->name('sales.gdns.create');

Route::get('/purchase/{purchase}/grn/create', [PurchaseGrnController::class, 'create'])->name('purchases.grns.create');

Route::get('/gdns/{gdn}/print', [GdnController::class, 'printed'])->name('gdns.print');

Route::get('/sivs/{siv}/print', [SivController::class, 'printed'])->name('sivs.print');

Route::post('purchase-orders/{purchaseOrder}/close', [PurchaseOrderController::class, 'close'])->name('purchase-orders.close');

Route::post('/gdns/{gdn}/approve', [GdnController::class, 'approve'])->name('gdns.approve');

Route::post('/gdns/{gdn}/subtract', [GdnController::class, 'subtract'])->name('gdns.subtract');

Route::post('/transfers/{transfer}/approve', [TransferController::class, 'approve'])->name('transfers.approve');

Route::post('/transfers/{transfer}/transfer', [TransferController::class, 'transfer'])->name('transfers.transfer');

Route::post('/grns/{grn}/approve', [GrnController::class, 'approve'])->name('grns.approve');

Route::post('/grns/{grn}/add', [GrnController::class, 'add'])->name('grns.add');

Route::post('/sivs/{siv}/approve', [SivController::class, 'approve'])->name('sivs.approve');

Route::post('/sivs/{siv}/execute', [SivController::class, 'execute'])->name('sivs.execute');

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

Route::resource('sivs', SivController::class);
