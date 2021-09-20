<?php

use App\Http\Controllers as Controllers;
use Illuminate\Support\Facades\Route;

Route::view('/', 'menu.index')->name('home');

Route::get('/product/uom/{product}', [Controllers\ProductController::class, 'getProductUOM']);

Route::get('/employees/{employee}/permissions/edit', [Controllers\PermissionController::class, 'edit'])->name('permissions.edit');

Route::get('/notifications/unread', [Controllers\NotificationController::class, 'getUnreadNotifications']);

Route::get('/notifications/{notification}/mark-as-read',
    [Controllers\NotificationController::class, 'markNotificationAsRead'])
    ->name('notifications.markAsRead');

Route::get('/warehouses/{warehouse}/products/{product}', Controllers\WarehouseProductController::class)
    ->name('warehouses-products')
    ->middleware('isFeatureAccessible:Inventory History');

Route::get('merchandises', [Controllers\MerchandiseController::class, 'index'])
    ->name('merchandises.index')
    ->middleware('isFeatureAccessible:Merchandise Inventory');

Route::get('merchandises/available', [Controllers\MerchandiseController::class, 'available'])
    ->name('merchandises.available')
    ->middleware('isFeatureAccessible:Merchandise Inventory');

Route::get('merchandises/reserved', [Controllers\MerchandiseController::class, 'reserved'])
    ->name('merchandises.reserved')
    ->middleware('isFeatureAccessible:Merchandise Inventory');

Route::get('merchandises/out-of-stock', [Controllers\MerchandiseController::class, 'outOfStock'])
    ->name('merchandises.out-of-stock')
    ->middleware('isFeatureAccessible:Merchandise Inventory');

Route::get('/sale/{sale}/gdn/create', Controllers\SaleGdnController::class)
    ->name('sales.gdns.create')
    ->middleware('isFeatureAccessible:Gdn Management');

Route::get('/purchases/{purchase}/grns/create', Controllers\PurchaseGrnController::class)
    ->name('purchases.grns.create')
    ->middleware('isFeatureAccessible:Grn Management');

Route::get('/gdns/{gdn}/sivs/create', Controllers\GdnSivController::class)
    ->name('gdns.sivs.create')
    ->middleware('isFeatureAccessible:Siv Management');

Route::get('/transfers/{transfer}/sivs/create', Controllers\TransferSivController::class)
    ->name('transfers.sivs.create')
    ->middleware('isFeatureAccessible:Siv Management');

Route::get('/proforma-invoices/{proforma_invoice}/gdns/create', Controllers\ProformaInvoiceGdnController::class)
    ->name('proforma-invoices.gdns.create')
    ->middleware('isFeatureAccessible:Proforma Invoice');

Route::get('/gdns/{gdn}/print', [Controllers\GdnController::class, 'printed'])->name('gdns.print');

Route::get('/sivs/{siv}/print', [Controllers\SivController::class, 'printed'])->name('sivs.print');

Route::get('/proforma-invoices/{proformaInvoice}/print', [Controllers\ProformaInvoiceController::class, 'printed'])->name('proforma-invoices.print');

Route::get('/returns/{return}/print', [Controllers\ReturnController::class, 'printed'])->name('returns.print');

Route::get('/password/edit', [Controllers\PasswordResetController::class, 'edit'])->name('password.edit');

Route::get('/tenders/{tender}/print', [Controllers\TenderController::class, 'printed'])->name('tenders.print');

Route::get('/tenders/{tender}/reading', [Controllers\TenderReadingController::class, 'edit'])->name('tenders.reading.edit');

Route::patch('/tenders/{tender}/reading', [Controllers\TenderReadingController::class, 'update'])->name('tenders.reading.update');

Route::patch('/employees/{employee}/permissions', [Controllers\PermissionController::class, 'update'])->name('permissions.update');

Route::patch('/notifications/mark-all-read',
    [Controllers\NotificationController::class, 'markAllNotificationsAsRead'])
    ->name('notifications.markAllAsRead');

Route::patch('/password/update', [Controllers\PasswordResetController::class, 'update'])->name('password.update');

Route::post('purchase-orders/{purchaseOrder}/close', [Controllers\PurchaseOrderController::class, 'close'])->name('purchase-orders.close');

Route::post('/gdns/{gdn}/approve', [Controllers\GdnController::class, 'approve'])->name('gdns.approve');

Route::post('/gdns/{gdn}/subtract', [Controllers\GdnController::class, 'subtract'])->name('gdns.subtract');

Route::post('/transfers/{transfer}/approve', [Controllers\TransferController::class, 'approve'])->name('transfers.approve');

Route::post('/transfers/{transfer}/transfer', [Controllers\TransferController::class, 'transfer'])->name('transfers.transfer');

Route::post('/grns/{grn}/approve', [Controllers\GrnController::class, 'approve'])->name('grns.approve');

Route::post('/grns/{grn}/add', [Controllers\GrnController::class, 'add'])->name('grns.add');

Route::post('/sivs/{siv}/approve', [Controllers\SivController::class, 'approve'])->name('sivs.approve');

Route::post('/proforma-invoices/{proformaInvoice}/cancel', [Controllers\ProformaInvoiceController::class, 'cancel'])->name('proforma-invoices.cancel');

Route::post('/proforma-invoices/{proformaInvoice}/convert', [Controllers\ProformaInvoiceController::class, 'convert'])->name('proforma-invoices.convert');

Route::post('/damages/{damage}/approve', [Controllers\DamageController::class, 'approve'])->name('damages.approve');

Route::post('/damages/{damage}/subtract', [Controllers\DamageController::class, 'subtract'])->name('damages.subtract');

Route::post('/adjustments/{adjustment}/approve', [Controllers\AdjustmentController::class, 'approve'])->name('adjustments.approve');

Route::post('/adjustments/{adjustment}/adjust', [Controllers\AdjustmentController::class, 'adjust'])->name('adjustments.adjust');

Route::post('/returnns/{return}/approve', [Controllers\ReturnController::class, 'approve'])->name('returns.approve');

Route::post('/returnns/{return}/return', [Controllers\ReturnController::class, 'add'])->name('returns.add');

Route::post('/reservations/{reservation}/approve', [Controllers\ReservationController::class, 'approve'])->name('reservations.approve');

Route::post('/reservations/{reservation}/convert', [Controllers\ReservationController::class, 'convert'])->name('reservations.convert');

Route::post('/reservations/{reservation}/cancel', [Controllers\ReservationController::class, 'cancel'])->name('reservations.cancel');

Route::post('/reservations/{reservation}/reserve', [Controllers\ReservationController::class, 'reserve'])->name('reservations.reserve');

Route::resource('products', Controllers\ProductController::class);

Route::resource('categories', Controllers\ProductCategoryController::class);

Route::resource('employees', Controllers\EmployeeController::class);

Route::resource('companies', Controllers\CompanyController::class);

Route::resource('purchases', Controllers\PurchaseController::class);

Route::resource('sales', Controllers\SaleController::class);

Route::resource('notifications', Controllers\NotificationController::class)->only("index");

Route::resource('warehouses.merchandises', Controllers\MerchandiseLevelByWarehouseController::class);

Route::resource('suppliers', Controllers\SupplierController::class);

Route::resource('warehouses', Controllers\WarehouseController::class);

Route::resource('customers', Controllers\CustomerController::class);

Route::resource('gdns', Controllers\GdnController::class);

Route::resource('transfers', Controllers\TransferController::class);

Route::resource('purchase-orders', Controllers\PurchaseOrderController::class);

Route::resource('grns', Controllers\GrnController::class);

Route::resource('general-tender-checklists', Controllers\GeneralTenderChecklistController::class);

Route::resource('tender-checklist-types', Controllers\TenderChecklistTypeController::class);

Route::resource('tender-statuses', Controllers\TenderStatusController::class);

Route::resource('tenders', Controllers\TenderController::class);

Route::resource('tender-checklists', Controllers\TenderChecklistController::class);

Route::resource('sivs', Controllers\SivController::class);

Route::resource('proforma-invoices', Controllers\ProformaInvoiceController::class);

Route::resource('damages', Controllers\DamageController::class);

Route::resource('adjustments', Controllers\AdjustmentController::class);

Route::resource('returns', Controllers\ReturnController::class);

Route::resource('reservations', Controllers\ReservationController::class);
