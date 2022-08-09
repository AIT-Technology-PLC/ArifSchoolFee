<?php

use App\Http\Controllers\Action as Action;
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

Route::post('/grns/import',
    [Action\GrnController::class, 'import'])
    ->name('grns.import');

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

Route::get('/reservations/{reservation}/print',
    [Action\ReservationController::class, 'printed'])
    ->name('reservations.print');

// Tender Checklist Assignments
Route::get('/tender-checklist-assignments/{tender}/edit',
    [Action\TenderChecklistAssignmentController::class, 'edit'])
    ->name('tender-checklists-assignments.edit');

Route::patch('/tender-checklist-assignments/{tender}',
    [Action\TenderChecklistAssignmentController::class, 'update'])
    ->name('tender-checklists-assignments.update');

//Customers
Route::post('/customers/import',
    [Action\CustomerController::class, 'import'])
    ->name('customers.import');

Route::get('/customers/{customer}/settle',
    [Action\CustomerController::class, 'settle'])
    ->name('customers.settle');

Route::post('/customers/{customer}/settle-credit',
    [Action\CustomerController::class, 'settleCredit'])
    ->name('customers.settle_credit');

Route::post('/suppliers/import',
    [Action\SupplierController::class, 'import'])
    ->name('suppliers.import');

Route::post('/tender-statuses/import',
    [Action\TenderStatusController::class, 'import'])
    ->name('tender-statuses.import');

// Products
Route::post('/products/import',
    [Action\ProductController::class, 'import'])
    ->name('products.import');

// Employees
Route::post('/employees/import',
    [Action\EmployeeController::class, 'import'])
    ->name('employees.import');

Route::post('/warehouses/import',
    [Action\WarehouseController::class, 'import'])
    ->name('warehouses.import');

// Product Categories
Route::post('/categories/import',
    [Action\ProductCategoryController::class, 'import'])
    ->name('categories.import');

// Job
Route::post('/jobs/{job}/approve',
    [Action\JobController::class, 'approve'])
    ->name('jobs.approve');

Route::post('/jobs/{job}/add-to-wip',
    [Action\JobController::class, 'addToWorkInProcess'])
    ->name('jobs.add_to_wip');

Route::post('/jobs/{job}/add-to-available',
    [Action\JobController::class, 'addToAvailable'])
    ->name('jobs.add_to_available');

Route::post('/jobs/{job}/close',
    [Action\JobController::class, 'close'])
    ->name('jobs.close');

// JobExtra
Route::post('/job-extras/{job_extra}/add',
    [Action\JobExtraController::class, 'add'])
    ->name('job-extras.add');

Route::post('/job-extras/{job_extra}/subtract',
    [Action\JobExtraController::class, 'subtract'])
    ->name('job-extras.subtract');

// Employee Transfer
Route::post('/employee-transfers/{employee_transfer}/approve',
    [Action\EmployeeTransferController::class, 'approve'])
    ->name('employee-transfers.approve');

// Warning
Route::post('/warnings/{warning}/approve',
    [Action\WarningController::class, 'approve'])
    ->name('warnings.approve');

// Attendance
Route::post('/attendances/{attendance}/approve',
    [Action\AttendanceController::class, 'approve'])
    ->name('attendances.approve');

Route::post('/attendances/{attendance}/cancel',
    [Action\AttendanceController::class, 'cancel'])
    ->name('attendances.cancel');

Route::controller(Action\TransactionController::class)->group(function () {
    Route::post('/transactions/{transaction}/approve', 'approve')->name('transactions.approve');
    Route::post('/transactions/{transaction}/subtract', 'subtract')->name('transactions.subtract');
    Route::post('/transactions/{transaction}/add', 'add')->name('transactions.add');
    Route::get('/transactions/{transaction}/print', 'printed')->name('transactions.print');
    Route::get('/transactions/{transaction}/convert-to', 'convertTo')->name('transactions.convert_to');
    Route::get('/transactions/{transaction}/convert-from', 'convertFrom')->name('transactions.convert_from');
    Route::post('/transactions/{transaction}/update-status', 'updateStatus')->name('transactions.update_status');
});

Route::controller(Action\TransactionFieldController::class)->group(function () {
    Route::post('/transaction-fields/{transaction_field}/subtract', 'subtract')->name('transaction-fields.subtract');
    Route::post('/transaction-fields/{transaction_field}/add', 'add')->name('transaction-fields.add');
});

Route::controller(Action\SaleController::class)->group(function () {
    Route::post('/sales/{sale}/approve', 'approve')->name('sales.approve');
    Route::post('/sales/{sale}/cancel', 'cancel')->name('sales.cancel');
    Route::get('/sales/{sale}/print', 'printed')->name('sales.print');
});

Route::post('/job-planners/print',
    [Action\JobPlannerController::class, 'printed'])
    ->name('job-planners.print');

Route::post('/adjustments/import',
    [Action\AdjustmentController::class, 'import'])
    ->name('adjustments.import');

Route::post('/gdns/import',
    [Action\GdnController::class, 'import'])
    ->name('gdns.import');

Route::post('/gdns/{gdn}/convert-to-sale',
    [Action\GdnController::class, 'convertToSale'])
    ->name('gdns.convert_to_sale');

// Leave
Route::post('/leaves/{leaf}/approve',
    [Action\LeaveController::class, 'approve'])
    ->name('leaves.approve');

Route::post('/leaves/{leaf}/cancel',
    [Action\LeaveController::class, 'cancel'])
    ->name('leaves.cancel');

// Advancement
Route::post('/advancements/{advancement}/approve',
    [Action\AdvancementController::class, 'approve'])
    ->name('advancements.approve');

// BOM
Route::post('/bill-of-materials/{bill_of_material}/approve',
    [Action\BillOfMaterialController::class, 'approve'])
    ->name('bill-of-materials.approve');

// Expense Claim
Route::post('/expense-claims/{expense_claim}/approve',
    [Action\ExpenseClaimController::class, 'approve'])
    ->name('expense-claims.approve');

Route::post('/expense-claims/{expense_claim}/reject',
    [Action\ExpenseClaimController::class, 'reject'])
    ->name('expense-claims.reject');

Route::get('/expense-claims/request/create',
    [Action\ExpenseClaimController::class, 'createExpenseClaim'])
    ->name('expense-claims.request.create');

Route::post('/expense-claims/request',
    [Action\ExpenseClaimController::class, 'storeExpenseClaim'])
    ->name('expense-claims.request.store');

// Earning
Route::post('/earnings/{earning}/approve',
    [Action\EarningController::class, 'approve'])
    ->name('earnings.approve');

// Announcement
Route::post('/announcements/{announcement}/approve',
    [Action\AnnouncementController::class, 'approve'])
    ->name('announcements.approve');

Route::get('/announcements/board',
    [Action\AnnouncementController::class, 'board'])
    ->name('announcements.board');

// Compensation Adjustment
Route::post('/compensation-adjustments/{compensation_adjustment}/approve',
    [Action\CompensationAdjustmentController::class, 'approve'])
    ->name('compensation-adjustments.approve');

Route::post('/compensation-adjustments/{compensation_adjustment}/cancel',
    [Action\CompensationAdjustmentController::class, 'cancel'])
    ->name('compensation-adjustments.cancel');