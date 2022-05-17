<?php
use Illuminate\Support\Facades\Route;

// Permissions
Route::get('/employees/{employee}/permissions/edit',
    [Action\PermissionController::class, 'edit'])
    ->name('permissions.edit');

Route::patch('/employees/{employee}/permissions',
    [Action\PermissionController::class, 'update'])
    ->name('permissions.update');

// Purchases
Route::get('/purchases/{purchase}/convert-to-grn',
    [Action\PurchaseController::class, 'convertToGrn'])
    ->name('purchases.convert_to_grn');

Route::post('/purchases/{purchase}/approve',
    [Action\PurchaseController::class, 'approve'])
    ->name('purchases.approve');

Route::post('/purchases/{purchase}/purchase',
    [Action\PurchaseController::class, 'purchase'])
    ->name('purchases.purchase');

Route::post('/purchases/{purchase}/close',
    [Action\PurchaseController::class, 'close'])
    ->name('purchases.close');

// Transfers
Route::post('/transfers/{transfer}/convert-to-siv',
    [Action\TransferController::class, 'convertToSiv'])
    ->name('transfers.convert_to_siv');

Route::post('/transfers/{transfer}/approve',
    [Action\TransferController::class, 'approve'])
    ->name('transfers.approve');

Route::post('/transfers/{transfer}/subtract',
    [Action\TransferController::class, 'subtract'])
    ->name('transfers.subtract');

Route::post('/transfers/{transfer}/add',
    [Action\TransferController::class, 'add'])
    ->name('transfers.add');

Route::post('/transfers/{transfer}/close',
    [Action\TransferController::class, 'close'])
    ->name('transfers.close');

// Proforma Invoices
Route::get('/proforma-invoices/{proformaInvoice}/print',
    [Action\ProformaInvoiceController::class, 'printed'])
    ->name('proforma-invoices.print');

Route::get('/proforma-invoices/{proforma_invoice}/convert-to-gdn',
    [Action\ProformaInvoiceController::class, 'convertToGdn'])
    ->name('proforma-invoices.convert_to_gdn');

Route::post('/proforma-invoices/{proformaInvoice}/cancel',
    [Action\ProformaInvoiceController::class, 'cancel'])
    ->name('proforma-invoices.cancel');

Route::post('/proforma-invoices/{proformaInvoice}/convert',
    [Action\ProformaInvoiceController::class, 'convert'])
    ->name('proforma-invoices.convert');

Route::post('/proforma-invoices/{proformaInvoice}/close',
    [Action\ProformaInvoiceController::class, 'close'])
    ->name('proforma-invoices.close');

// Gdns
Route::post('/gdns/{gdn}/convert-to-siv',
    [Action\GdnController::class, 'convertToSiv'])
    ->name('gdns.convert_to_siv');

Route::post('/gdns/{gdn}/close',
    [Action\GdnController::class, 'close'])
    ->name('gdns.close');

Route::get('/gdns/{gdn}/print',
    [Action\GdnController::class, 'printed'])
    ->name('gdns.print');

Route::post('/gdns/{gdn}/approve',
    [Action\GdnController::class, 'approve'])
    ->name('gdns.approve');

Route::post('/gdns/{gdn}/subtract',
    [Action\GdnController::class, 'subtract'])
    ->name('gdns.subtract');

Route::post('/gdns/{gdn}/convert-to-credit',
    [Action\GdnController::class, 'convertToCredit'])
    ->name('gdns.convert_to_credit');

// Sivs
Route::get('/sivs/{siv}/print',
    [Action\SivController::class, 'printed'])
    ->name('sivs.print');

Route::post('/sivs/{siv}/approve',
    [Action\SivController::class, 'approve'])
    ->name('sivs.approve');

// Returns
Route::get('/returns/{return}/print',
    [Action\ReturnController::class, 'printed'])
    ->name('returns.print');

Route::post('/returnns/{return}/approve',
    [Action\ReturnController::class, 'approve'])
    ->name('returns.approve');

Route::post('/returnns/{return}/return',
    [Action\ReturnController::class, 'add'])
    ->name('returns.add');

// Tenders
Route::get('/tenders/{tender}/print',
    [Action\TenderController::class, 'printed'])
    ->name('tenders.print');

// Notifications
Route::patch('/notifications/mark-all-read',
    [Action\NotificationController::class, 'markAllAsRead'])
    ->name('notifications.markAllAsRead');

Route::post('/notifications/delete-all',
    [Action\NotificationController::class, 'deleteAll'])
    ->name('notifications.delete_all');

// Grns
Route::post('/grns/{grn}/approve',
    [Action\GrnController::class, 'approve'])
    ->name('grns.approve');

Route::post('/grns/{grn}/add',
    [Action\GrnController::class, 'add'])
    ->name('grns.add');

// Damages
Route::post('/damages/{damage}/approve',
    [Action\DamageController::class, 'approve'])
    ->name('damages.approve');

Route::post('/damages/{damage}/subtract',
    [Action\DamageController::class, 'subtract'])
    ->name('damages.subtract');

// Adjustments
Route::post('/adjustments/{adjustment}/approve',
    [Action\AdjustmentController::class, 'approve'])
    ->name('adjustments.approve');

Route::post('/adjustments/{adjustment}/adjust',
    [Action\AdjustmentController::class, 'adjust'])
    ->name('adjustments.adjust');

// Reservations
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

// Tender Checklist Assignments
Route::get('/tender-checklist-assignments/{tender}/edit',
    [Action\TenderChecklistAssignmentController::class, 'edit'])
    ->name('tender-checklists-assignments.edit');

Route::patch('/tender-checklist-assignments/{tender}',
    [Action\TenderChecklistAssignmentController::class, 'update'])
    ->name('tender-checklists-assignments.update');

Route::post('/customers/import',
    [Action\CustomerController::class, 'import'])
    ->name('customers.import');

Route::post('/suppliers/import',
    [Action\SupplierController::class, 'import'])
    ->name('suppliers.import');

Route::post('/tender-status/import',
    [Action\TenderStatusController::class, 'import'])
    ->name('tender-status.import');