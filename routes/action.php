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
    [Action\SivController::class, 'printed'])
    ->name('sivs.print');

Route::get('/proforma-invoices/{proformaInvoice}/print',
    [Action\ProformaInvoiceController::class, 'printed'])
    ->name('proforma-invoices.print');

Route::get('/returns/{return}/print',
    [Action\ReturnController::class, 'printed'])
    ->name('returns.print');

Route::get('/password/edit',
    [Auth\PasswordResetController::class, 'edit'])
    ->name('password.edit');

Route::get('/tenders/{tender}/print',
    [Action\TenderController::class, 'printed'])
    ->name('tenders.print');

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
    [Action\GrnController::class, 'approve'])
    ->name('grns.approve');

Route::post('/grns/{grn}/add',
    [Action\GrnController::class, 'add'])
    ->name('grns.add');

Route::post('/sivs/{siv}/approve',
    [Action\SivController::class, 'approve'])
    ->name('sivs.approve');

Route::post('/proforma-invoices/{proformaInvoice}/cancel',
    [Action\ProformaInvoiceController::class, 'cancel'])
    ->name('proforma-invoices.cancel');

Route::post('/proforma-invoices/{proformaInvoice}/convert',
    [Action\ProformaInvoiceController::class, 'convert'])
    ->name('proforma-invoices.convert');

Route::post('/damages/{damage}/approve',
    [Action\DamageController::class, 'approve'])
    ->name('damages.approve');

Route::post('/damages/{damage}/subtract',
    [Action\DamageController::class, 'subtract'])
    ->name('damages.subtract');

Route::post('/adjustments/{adjustment}/approve',
    [Action\AdjustmentController::class, 'approve'])
    ->name('adjustments.approve');

Route::post('/adjustments/{adjustment}/adjust',
    [Action\AdjustmentController::class, 'adjust'])
    ->name('adjustments.adjust');

Route::post('/returnns/{return}/approve',
    [Action\ReturnController::class, 'approve'])
    ->name('returns.approve');

Route::post('/returnns/{return}/return',
    [Action\ReturnController::class, 'add'])
    ->name('returns.add');

Route::post('/reservations/{reservation}/approve',
    [Action\ReservationController::class, 'approve'])
    ->name('reservations.approve');

Route::post('/reservations/{reservation}/convert',
    [Action\ReservationController::class, 'convert'])
    ->name('reservations.convert');

Route::post('/reservations/{reservation}/cancel',
    [Action\ReservationController::class, 'cancel'])
    ->name('reservations.cancel');

Route::post('/reservations/{reservation}/reserve',
    [Action\ReservationController::class, 'reserve'])
    ->name('reservations.reserve');
