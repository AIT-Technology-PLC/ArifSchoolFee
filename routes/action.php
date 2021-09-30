<?php

use App\Http\Controllers as Controllers;
use App\Http\Controllers\Action as Action;
use App\Http\Controllers\Auth as Auth;
use Illuminate\Support\Facades\Route;

Route::get('/employees/{employee}/permissions/edit',
    [Controllers\PermissionController::class, 'edit'])
    ->name('permissions.edit');

Route::get('/sale/{sale}/gdn/create',
    Controllers\SaleGdnController::class)
    ->name('sales.gdns.create');

Route::get('/purchases/{purchase}/grns/create',
    Controllers\PurchaseGrnController::class)
    ->name('purchases.grns.create');

Route::get('/gdns/{gdn}/sivs/create',
    Controllers\GdnSivController::class)
    ->name('gdns.sivs.create');

Route::get('/transfers/{transfer}/sivs/create',
    Controllers\TransferSivController::class)
    ->name('transfers.sivs.create');

Route::get('/proforma-invoices/{proforma_invoice}/gdns/create',
    Controllers\ProformaInvoiceGdnController::class)
    ->name('proforma-invoices.gdns.create');

Route::get('/gdns/{gdn}/print',
    [Action\GdnController::class, 'printed'])
    ->name('gdns.print');

Route::post('/gdns/{gdn}/approve',
    [Action\GdnController::class, 'approve'])
    ->name('gdns.approve');

Route::post('/gdns/{gdn}/subtract',
    [Action\GdnController::class, 'subtract'])
    ->name('gdns.subtract');

Route::get('/sivs/{siv}/print',
    [Controllers\SivController::class, 'printed'])
    ->name('sivs.print');

Route::get('/proforma-invoices/{proformaInvoice}/print',
    [Controllers\ProformaInvoiceController::class, 'printed'])
    ->name('proforma-invoices.print');

Route::get('/returns/{return}/print',
    [Controllers\ReturnController::class, 'printed'])
    ->name('returns.print');

Route::get('/password/edit',
    [Auth\PasswordResetController::class, 'edit'])
    ->name('password.edit');

Route::get('/tenders/{tender}/print',
    [Controllers\TenderController::class, 'printed'])
    ->name('tenders.print');

Route::get('/tenders/{tender}/reading',
    [Controllers\TenderReadingController::class, 'edit'])
    ->name('tenders.reading.edit');

Route::patch('/tenders/{tender}/reading',
    [Controllers\TenderReadingController::class, 'update'])
    ->name('tenders.reading.update');

Route::patch('/employees/{employee}/permissions',
    [Controllers\PermissionController::class, 'update'])
    ->name('permissions.update');

Route::patch('/notifications/mark-all-read',
    [Action\NotificationController::class, 'markAllAsRead'])
    ->name('notifications.markAllAsRead');

Route::patch('/password/update',
    [Auth\PasswordResetController::class, 'update'])
    ->name('password.update');

Route::post('purchase-orders/{purchaseOrder}/close',
    [Action\PurchaseOrderController::class, 'close'])
    ->name('purchase-orders.close');

Route::post('/transfers/{transfer}/approve',
    [Action\TransferController::class, 'approve'])
    ->name('transfers.approve');

Route::post('/transfers/{transfer}/transfer',
    [Action\TransferController::class, 'transfer'])
    ->name('transfers.transfer');

Route::post('/grns/{grn}/approve',
    [Controllers\GrnController::class, 'approve'])
    ->name('grns.approve');

Route::post('/grns/{grn}/add',
    [Controllers\GrnController::class, 'add'])
    ->name('grns.add');

Route::post('/sivs/{siv}/approve',
    [Controllers\SivController::class, 'approve'])
    ->name('sivs.approve');

Route::post('/proforma-invoices/{proformaInvoice}/cancel',
    [Controllers\ProformaInvoiceController::class, 'cancel'])
    ->name('proforma-invoices.cancel');

Route::post('/proforma-invoices/{proformaInvoice}/convert',
    [Controllers\ProformaInvoiceController::class, 'convert'])
    ->name('proforma-invoices.convert');

Route::post('/damages/{damage}/approve',
    [Controllers\DamageController::class, 'approve'])
    ->name('damages.approve');

Route::post('/damages/{damage}/subtract',
    [Controllers\DamageController::class, 'subtract'])
    ->name('damages.subtract');

Route::post('/adjustments/{adjustment}/approve',
    [Controllers\AdjustmentController::class, 'approve'])
    ->name('adjustments.approve');

Route::post('/adjustments/{adjustment}/adjust',
    [Controllers\AdjustmentController::class, 'adjust'])
    ->name('adjustments.adjust');

Route::post('/returnns/{return}/approve',
    [Controllers\ReturnController::class, 'approve'])
    ->name('returns.approve');

Route::post('/returnns/{return}/return',
    [Controllers\ReturnController::class, 'add'])
    ->name('returns.add');

Route::post('/reservations/{reservation}/approve',
    [Controllers\ReservationController::class, 'approve'])
    ->name('reservations.approve');

Route::post('/reservations/{reservation}/convert',
    [Controllers\ReservationController::class, 'convert'])
    ->name('reservations.convert');

Route::post('/reservations/{reservation}/cancel',
    [Controllers\ReservationController::class, 'cancel'])
    ->name('reservations.cancel');

Route::post('/reservations/{reservation}/reserve',
    [Controllers\ReservationController::class, 'reserve'])
    ->name('reservations.reserve');
