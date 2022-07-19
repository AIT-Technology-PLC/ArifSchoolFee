<?php

use App\Http\Controllers\Resource as Resource;
use Illuminate\Support\Facades\Route;

Route::resource('products', Resource\ProductController::class)->except('show');

Route::resource('categories', Resource\ProductCategoryController::class);

Route::resource('employees', Resource\EmployeeController::class);

Route::resource('companies', Resource\CompanyController::class)->only(['edit', 'update']);

Route::resource('purchases', Resource\PurchaseController::class);

Route::resource('purchase-details', Resource\PurchaseDetailController::class)->only('destroy');

Route::resource('sales', Resource\SaleController::class);

Route::resource('sale-details', Resource\SaleDetailController::class)->only('destroy');

Route::resource('notifications', Resource\NotificationController::class)->except(['create', 'store', 'edit']);

Route::resource('suppliers', Resource\SupplierController::class)->except('show');

Route::resource('warehouses', Resource\WarehouseController::class)->except('show');

Route::resource('customers', Resource\CustomerController::class)->except('show');

Route::resource('gdns', Resource\GdnController::class);

Route::resource('gdn-details', Resource\GdnDetailController::class)->only('destroy');

Route::resource('transfers', Resource\TransferController::class);

Route::resource('transfer-details', Resource\TransferDetailController::class)->only('destroy');

Route::resource('grns', Resource\GrnController::class);

Route::resource('grn-details', Resource\GrnDetailController::class)->only('destroy');

Route::resource('general-tender-checklists', Resource\GeneralTenderChecklistController::class)->except('show');

Route::resource('tender-checklist-types', Resource\TenderChecklistTypeController::class)->except('show');

Route::resource('tender-statuses', Resource\TenderStatusController::class)->except('show');

Route::resource('tenders', Resource\TenderController::class);

Route::resource('tender-lots', Resource\TenderLotController::class)->only('destroy');

Route::resource('tender-lot-details', Resource\TenderLotDetailController::class)->only('destroy');

Route::resource('tender-readings', Resource\TenderReadingController::class)->only(['edit', 'update']);

Route::resource('tenders.tender-checklists', Resource\TenderChecklistController::class)->except(['index', 'show'])->shallow();

Route::resource('tender-opportunities', Resource\TenderOpportunityController::class);

Route::resource('sivs', Resource\SivController::class);

Route::resource('siv-details', Resource\SivDetailController::class)->only('destroy');

Route::resource('proforma-invoices', Resource\ProformaInvoiceController::class);

Route::resource('proforma-invoice-details', Resource\ProformaInvoiceDetailController::class)->only('destroy');

Route::resource('damages', Resource\DamageController::class);

Route::resource('damage-details', Resource\DamageDetailController::class)->only('destroy');

Route::resource('adjustments', Resource\AdjustmentController::class);

Route::resource('adjustment-details', Resource\AdjustmentDetailController::class)->only('destroy');

Route::resource('returns', Resource\ReturnController::class);

Route::resource('return-details', Resource\ReturnDetailController::class)->only('destroy');

Route::resource('reservations', Resource\ReservationController::class);

Route::resource('reservation-details', Resource\ReservationDetailController::class)->only('destroy');

Route::resource('credits', Resource\CreditController::class);

Route::resource('credits.credit-settlements', Resource\CreditSettlementController::class)->except(['index', 'show'])->shallow();

Route::resource('customers.credits', Resource\CustomerCreditController::class)->only('index');

Route::resource('prices', Resource\PriceController::class)->except('show');

Route::resource('pads', Resource\PadController::class);

Route::resource('pad-fields', Resource\PadFieldController::class)->only('destroy');

Route::resource('pads.transactions', Resource\TransactionController::class)->shallow()->except(['store', 'update']);

Route::resource('transaction-fields', Resource\TransactionFieldController::class)->only(['destroy']);

Route::resource('bill-of-materials', Resource\BillOfMaterialController::class);

Route::resource('bill-of-material-details', Resource\BillOfMaterialDetailController::class)->only(['destroy']);

Route::resource('job-planners', Resource\JobPlannerController::class)->only(['create', 'store']);

Route::resource('jobs', Resource\JobController::class);

Route::resource('job-details', Resource\JobDetailController::class)->only(['destroy']);

Route::resource('jobs.job-extras', Resource\JobExtraController::class)->shallow()->except(['index', 'create', 'show']);

Route::resource('departments', Resource\DepartmentController::class);

Route::resource('employee-transfers', Resource\EmployeeTransferController::class);

Route::resource('employee-transfer-details', Resource\EmployeeTransferDetailController::class)->only(['destroy']);

Route::resource('warnings', Resource\WarningController::class);
