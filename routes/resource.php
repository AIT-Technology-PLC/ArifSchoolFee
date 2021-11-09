<?php

use App\Http\Controllers\Resource as Resource;
use Illuminate\Support\Facades\Route;

Route::resource('products', Resource\ProductController::class);

Route::resource('categories', Resource\ProductCategoryController::class);

Route::resource('employees', Resource\EmployeeController::class);

Route::resource('companies', Resource\CompanyController::class);

Route::resource('purchases', Resource\PurchaseController::class);

Route::resource('purchase-details', Resource\PurchaseDetailController::class);

Route::resource('sales', Resource\SaleController::class);

Route::resource('sale-details', Resource\SaleDetailController::class);

Route::resource('notifications', Resource\NotificationController::class);

Route::resource('suppliers', Resource\SupplierController::class);

Route::resource('warehouses', Resource\WarehouseController::class);

Route::resource('customers', Resource\CustomerController::class);

Route::resource('gdns', Resource\GdnController::class);

Route::resource('gdn-details', Resource\GdnDetailController::class);

Route::resource('transfers', Resource\TransferController::class);

Route::resource('transfer-details', Resource\TransferDetailController::class);

Route::resource('purchase-orders', Resource\PurchaseOrderController::class);

Route::resource('purchase-order-details', Resource\PurchaseOrderDetailController::class);

Route::resource('grns', Resource\GrnController::class);

Route::resource('grn-details', Resource\GrnDetailController::class);

Route::resource('general-tender-checklists', Resource\GeneralTenderChecklistController::class);

Route::resource('tender-checklist-types', Resource\TenderChecklistTypeController::class);

Route::resource('tender-statuses', Resource\TenderStatusController::class);

Route::resource('tenders', Resource\TenderController::class);

Route::resource('tender-details', Resource\TenderDetailController::class);

Route::resource('tender-readings', Resource\TenderReadingController::class);

Route::resource('tender-checklists', Resource\TenderChecklistController::class);

Route::resource('sivs', Resource\SivController::class);

Route::resource('siv-details', Resource\SivDetailController::class);

Route::resource('proforma-invoices', Resource\ProformaInvoiceController::class);

Route::resource('proforma-invoice-details', Resource\ProformaInvoiceDetailController::class);

Route::resource('damages', Resource\DamageController::class);

Route::resource('damage-details', Resource\DamageDetailController::class);

Route::resource('adjustments', Resource\AdjustmentController::class);

Route::resource('adjustment-details', Resource\AdjustmentDetailController::class);

Route::resource('returns', Resource\ReturnController::class);

Route::resource('return-details', Resource\ReturnDetailController::class);

Route::resource('reservations', Resource\ReservationController::class);

Route::resource('reservation-details', Resource\ReservationDetailController::class);

Route::resource('credits', Resource\CreditController::class);

Route::resource('credits.credit-settlements', Resource\CreditSettlementController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])->shallow();
