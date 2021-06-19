<?php

use App\Http\Controllers as Controllers;
use Illuminate\Support\Facades\Route;

// Route::get('/', 'HomeController@index')->name('home');

Route::get('/', [Controllers\MenuController::class, 'index'])->name('home');

Route::get('/product/uom/{product}', [Controllers\ProductController::class, 'getProductUOM']);

Route::get('/employees/{employee}/permissions/edit', [Controllers\PermissionController::class, 'edit'])->name('permissions.edit');

Route::get('/notifications/unread', [Controllers\NotificationController::class, 'getUnreadNotifications']);

Route::get('/notifications/{notification}/mark-as-read',
    [Controllers\NotificationController::class, 'markNotificationAsRead'])
    ->name('notifications.markAsRead');

Route::get('/warehouses/{warehouse}/products/{product}', Controllers\WarehouseProductController::class)
    ->name('warehouses-products')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Inventory History');

Route::get('merchandises', Controllers\MerchandiseController::class)
    ->name('merchandises.index')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Merchandise Inventory');

Route::get('/sale/{sale}/gdn/create', Controllers\SaleGdnController::class)
    ->name('sales.gdns.create')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Gdn Management');

Route::get('/purchases/{purchase}/grns/create', Controllers\PurchaseGrnController::class)
    ->name('purchases.grns.create')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Grn Management');

Route::get('/gdns/{gdn}/sivs/create', Controllers\GdnSivController::class)
    ->name('gdns.sivs.create')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Siv Management');

Route::get('/transfers/{transfer}/sivs/create', Controllers\TransferSivController::class)
    ->name('transfers.sivs.create')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Siv Management');

Route::get('/proforma-invoices/{proforma_invoice}/gdns/create', Controllers\ProformaInvoiceGdnController::class)
    ->name('proforma-invoices.gdns.create')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Proforma Invoice');

Route::get('/gdns/{gdn}/print', [Controllers\GdnController::class, 'printed'])->name('gdns.print');

Route::get('/sivs/{siv}/print', [Controllers\SivController::class, 'printed'])->name('sivs.print');

Route::get('/proforma-invoices/{proformaInvoice}/print', [Controllers\ProformaInvoiceController::class, 'printed'])->name('proforma-invoices.print');

Route::patch('/employees/{employee}/permissions', [Controllers\PermissionController::class, 'update'])->name('permissions.update');

Route::patch('/notifications/mark-all-read',
    [Controllers\NotificationController::class, 'markAllNotificationsAsRead'])
    ->name('notifications.markAllAsRead');

Route::post('purchase-orders/{purchaseOrder}/close', [Controllers\PurchaseOrderController::class, 'close'])->name('purchase-orders.close');

Route::post('/gdns/{gdn}/approve', [Controllers\GdnController::class, 'approve'])->name('gdns.approve');

Route::post('/gdns/{gdn}/subtract', [Controllers\GdnController::class, 'subtract'])->name('gdns.subtract');

Route::post('/transfers/{transfer}/approve', [Controllers\TransferController::class, 'approve'])->name('transfers.approve');

Route::post('/transfers/{transfer}/transfer', [Controllers\TransferController::class, 'transfer'])->name('transfers.transfer');

Route::post('/grns/{grn}/approve', [Controllers\GrnController::class, 'approve'])->name('grns.approve');

Route::post('/grns/{grn}/add', [Controllers\GrnController::class, 'add'])->name('grns.add');

Route::post('/sivs/{siv}/approve', [Controllers\SivController::class, 'approve'])->name('sivs.approve');

Route::post('/sivs/{siv}/execute', [Controllers\SivController::class, 'execute'])->name('sivs.execute');

Route::post('/proforma-invoices/{proformaInvoice}/cancel', [Controllers\ProformaInvoiceController::class, 'cancel'])->name('proforma-invoices.cancel');

Route::post('/proforma-invoices/{proformaInvoice}/convert', [Controllers\ProformaInvoiceController::class, 'convert'])->name('proforma-invoices.convert');

Route::post('/damages/{damage}/approve', [Controllers\DamageController::class, 'approve'])->name('damages.approve');

Route::post('/damages/{damage}/subtract', [Controllers\DamageController::class, 'subtract'])->name('damages.subtract');

Route::post('/adjustments/{adjustment}/approve', [Controllers\AdjustmentController::class, 'approve'])->name('adjustments.approve');

Route::post('/adjustments/{adjustment}/adjust', [Controllers\AdjustmentController::class, 'adjust'])->name('adjustments.adjust');

Route::resource('products', Controllers\ProductController::class);

Route::resource('categories', Controllers\ProductCategoryController::class);

Route::resource('employees', Controllers\EmployeeController::class);

Route::resource('companies', Controllers\CompanyController::class);

Route::resource('purchases', Controllers\PurchaseController::class);

Route::resource('sales', Controllers\SaleController::class);

Route::resource('prices', Controllers\PriceController::class);

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

Route::resource('tender-statuses', Controllers\TenderStatusController::class);

Route::resource('tenders', Controllers\TenderController::class);

Route::resource('tender-checklists', Controllers\TenderChecklistController::class);

Route::resource('sivs', Controllers\SivController::class);

Route::resource('proforma-invoices', Controllers\ProformaInvoiceController::class);

Route::resource('damages', Controllers\DamageController::class);

Route::resource('adjustments', Controllers\AdjustmentController::class);
