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

Route::resource('warehouses', Resource\WarehouseController::class)->except(['show', 'destroy']);

Route::resource('customers', Resource\CustomerController::class)->except(['store', 'update', 'show']);

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

Route::resource('prices', Resource\PriceController::class)->except(['show', 'edit', 'update']);

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

Route::resource('attendances', Resource\AttendanceController::class);

Route::resource('attendance-details', Resource\AttendanceDetailController::class)->only(['destroy']);

Route::resource('leave-categories', Resource\LeaveCategoryController::class)->except('show');

Route::resource('leaves', Resource\LeaveController::class);

Route::resource('advancements', Resource\AdvancementController::class);

Route::resource('advancement-details', Resource\AdvancementDetailController::class)->only(['destroy']);

Route::resource('expense-claims', Resource\ExpenseClaimController::class);

Route::resource('expense-claim-details', Resource\ExpenseClaimDetailController::class)->only(['destroy']);

Route::resource('announcements', Resource\AnnouncementController::class);

Route::resource('compensations', Resource\CompensationController::class)->except(['show', 'destroy']);

Route::resource('compensation-adjustments', Resource\CompensationAdjustmentController::class);

Route::resource('compensation-adjustment-details', Resource\CompensationAdjustmentDetailController::class)->only(['destroy']);

Route::post('subscriptions', [Resource\WebNotificationController::class, 'update']);

Route::post('subscriptions/delete', [Resource\WebNotificationController::class, 'destroy']);

// Manifest file (optional if VAPID is used)
Route::get('manifest.json', function () {
    return [
        'name' => config('app.name'),
        'gcm_sender_id' => config('webpush.gcm.sender_id'),
    ];
});

Route::resource('receivables', Resource\ReceivableController::class)->only(['index']);

Route::resource('debts', Resource\DebtController::class);

Route::resource('debts.debt-settlements', Resource\DebtSettlementController::class)->except(['index', 'show'])->shallow();

Route::resource('suppliers.debts', Resource\SupplierDebtController::class)->only('index');

Route::resource('payables', Resource\PayableController::class)->only(['index']);

Route::resource('expense-categories', Resource\ExpenseCategoryController::class)->except(['show']);

Route::resource('expenses', Resource\ExpenseController::class);

Route::resource('expense-details', Resource\ExpenseDetailController::class)->only(['destroy']);

Route::resource('contacts', Resource\ContactController::class)->except('show');

Route::resource('price-increments', Resource\PriceIncrementController::class);

Route::resource('price-increment-details', Resource\PriceIncrementDetailController::class)->only('destroy');

Route::resource('brands', Resource\BrandController::class)->except(['show']);

Route::resource('payrolls', Resource\PayrollController::class);

Route::resource('products.prices', Resource\ProductPriceController::class)->only(['index', 'edit', 'update'])->shallow();

Route::resource('customer-deposits', Resource\CustomerDepositController::class);

Route::resource('customers.customer-deposits', Resource\CustomerCustomerDepositController::class)->only('index');
