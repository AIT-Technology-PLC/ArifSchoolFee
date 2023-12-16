<?php

use App\Http\Controllers\Action as Action;
use Illuminate\Support\Facades\Route;

// Permissions
Route::controller(Action\PermissionController::class)
    ->name('permissions.')
    ->prefix('/employees/{employee}/permissions')
    ->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
    });

// Purchases
Route::controller(Action\PurchaseController::class)
    ->name('purchases.')
    ->prefix('/purchases/{purchase}')
    ->group(function () {
        Route::get('/convert-to-grn', 'convertToGrn')->name('convert_to_grn');
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/purchase', 'purchase')->name('purchase');
        Route::post('/close', 'close')->name('close');
        Route::post('/convert-to-debt', 'convertToDebt')->name('convert_to_debt');
        Route::post('/reject', 'reject')->name('reject');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/approve-and-purchase', 'approveAndPurchase')->name('approve_and_purchase');
    });

// Transfers
Route::controller(Action\TransferController::class)
    ->name('transfers.')
    ->prefix('/transfers/{transfer}')
    ->group(function () {
        Route::post('/convert-to-siv', 'convertToSiv')->name('convert_to_siv');
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/subtract', 'subtract')->name('subtract');
        Route::post('/add', 'add')->name('add');
        Route::post('/close', 'close')->name('close');
    });

// Proforma Invoices
Route::controller(Action\ProformaInvoiceController::class)
    ->name('proforma-invoices.')
    ->prefix('/proforma-invoices/{proforma_invoice}')
    ->group(function () {
        Route::get('/print', 'printed')->name('print');
        Route::post('/convert-to-gdn', 'convertToGdn')->name('convert_to_gdn');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/confirm', 'confirm')->name('confirm');
        Route::post('/close', 'close')->name('close');
        Route::post('/convert-to-sale', 'convertToSale')->name('convert_to_sale');
        Route::post('/restore', 'restore')->name('restore');
    });

// Gdns
Route::controller(Action\GdnController::class)
    ->name('gdns.')
    ->prefix('/gdns/{gdn}')
    ->group(function () {
        Route::post('/convert-to-siv', 'convertToSiv')->name('convert_to_siv');
        Route::post('/close', 'close')->name('close');
        Route::get('/print', 'printed')->name('print');
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/subtract', 'subtract')->name('subtract');
        Route::post('/convert-to-credit', 'convertToCredit')->name('convert_to_credit');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/approve-and-subtract', 'approveAndSubtract')->name('approve_and_subtract');
        Route::post('/convert-to-sale', 'convertToSale')->name('convert_to_sale');
    });

// Sivs
Route::controller(Action\SivController::class)
    ->name('sivs.')
    ->prefix('/sivs/{siv}')
    ->group(function () {
        Route::get('/print', 'printed')->name('print');
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/approve-and-subtract', 'approveAndSubtract')->name('approve_and_subtract');
        Route::post('/subtract', 'subtract')->name('subtract');
    });

// Returns
Route::controller(Action\ReturnController::class)
    ->name('returns.')
    ->prefix('/returns/{return}')
    ->group(function () {
        Route::get('/print', 'printed')->name('print');
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/return', 'add')->name('add');
        Route::post('/approve-and-return', 'approveAndAdd')->name('approve_and_add');
    });

// Tenders
Route::controller(Action\TenderController::class)
    ->name('tenders.')
    ->prefix('/tenders/{tender}')
    ->group(function () {
        Route::get('print', 'printed')->name('print');
    });

// Notifications
Route::controller(Action\NotificationController::class)
    ->name('notifications.')
    ->prefix('/notifications')
    ->group(function () {
        Route::patch('mark-all-read', 'markAllAsRead')->name('markAllAsRead');
        Route::post('delete-all', 'deleteAll')->name('delete_all');
    });

// Grns
Route::controller(Action\GrnController::class)
    ->name('grns.')
    ->prefix('/grns/{grn}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/add', 'add')->name('add');
        Route::post('/approve-and-add', 'approveAndAdd')->name('approve_and_add');
    });

// Damages
Route::controller(Action\DamageController::class)
    ->name('damages.')
    ->prefix('/damages/{damage}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/subtract', 'subtract')->name('subtract');
        Route::post('/approve-and-subtract', 'approveAndSubtract')->name('approve_and_subtract');
    });

// Adjustments
Route::controller(Action\AdjustmentController::class)
    ->name('adjustments.')
    ->prefix('/adjustments/{adjustment}')
    ->group(function () {
        Route::post('approve', 'approve')->name('approve');
        Route::post('adjust', 'adjust')->name('adjust');
        Route::post('approve-and-adjust', 'approveAndAdjust')->name('approve_and_adjust');
    });

// Reservations
Route::controller(Action\ReservationController::class)
    ->name('reservations.')
    ->prefix('/reservations/{reservation}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/convert-to-gdn', 'convertToGdn')->name('convert_to_gdn');
        Route::post('/convert-to-sale', 'convertToSale')->name('convert_to_sale');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/reserve', 'reserve')->name('reserve');
        Route::get('/print', 'printed')->name('print');
        Route::post('/approve-and-reserve', 'approveAndReserve')->name('approve_and_reserve');
    });

// Tender Checklist Assignments
Route::controller(Action\TenderChecklistAssignmentController::class)
    ->name('tender-checklists-assignments.')
    ->prefix('/tender-checklist-assignments/{tender}')
    ->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
    });

// Customers
Route::controller(Action\CustomerController::class)
    ->name('customers.')
    ->prefix('/customers/{customer}')
    ->group(function () {
        Route::get('/settle', 'settle')->name('settle');
        Route::post('/settle-credit', 'settleCredit')->name('settle_credit');
    });

// Suppliers
Route::controller(Action\SupplierController::class)
    ->name('suppliers.')
    ->prefix('/suppliers/{supplier}')
    ->group(function () {
        Route::get('/settle', 'settle')->name('settle');
        Route::post('/settle-debt', 'settleDebt')->name('settle_debt');
    });

// Import
Route::post('/gdns/import', [Action\GdnController::class, 'import'])->name('gdns.import');
Route::post('/grns/import', [Action\GrnController::class, 'import'])->name('grns.import');
Route::post('/adjustments/import', [Action\AdjustmentController::class, 'import'])->name('adjustments.import');
Route::post('/customers/import', [Action\CustomerController::class, 'import'])->name('customers.import');
Route::post('/suppliers/import', [Action\SupplierController::class, 'import'])->name('suppliers.import');
Route::post('/tender-statuses/import', [Action\TenderStatusController::class, 'import'])->name('tender-statuses.import');
Route::post('/products/import', [Action\ProductController::class, 'import'])->name('products.import');
Route::post('/employees/import', [Action\EmployeeController::class, 'import'])->name('employees.import');
Route::post('/employee-compensations/import', [Action\EmployeeCompensationController::class, 'import'])->name('employee-compensations.import');
Route::post('/warehouses/import', [Action\WarehouseController::class, 'import'])->name('warehouses.import');
Route::post('/categories/import', [Action\ProductCategoryController::class, 'import'])->name('categories.import');
Route::post('/prices/import', [Action\PriceController::class, 'import'])->name('prices.import');
Route::post('/contacts/import', [Action\ContactController::class, 'import'])->name('contacts.import');
Route::post('/brands/import', [Action\BrandController::class, 'import'])->name('brands.import');
Route::post('/credits/import', [Action\CreditController::class, 'import'])->name('credits.import');
Route::post('/debts/import', [Action\DebtController::class, 'import'])->name('debts.import');
Route::post('/cost-updates/import', [Action\CostUpdateController::class, 'import'])->name('cost-updates.import');

// Job
Route::controller(Action\JobController::class)
    ->name('jobs.')
    ->prefix('/jobs/{job}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/add-to-wip', 'addToWorkInProcess')->name('add_to_wip');
        Route::post('/add-to-available', 'addToAvailable')->name('add_to_available');
        Route::post('/close', 'close')->name('close');
        Route::get('/convert-to-sale', 'convertToSale')->name('convert_to_sale');
    });

// JobExtra
Route::controller(Action\JobExtraController::class)
    ->name('job-extras.')
    ->prefix('/job-extras/{job_extra}')
    ->group(function () {
        Route::post('/add', 'add')->name('add');
        Route::post('/subtract', 'subtract')->name('subtract');
    });

// Employee Transfer
Route::controller(Action\EmployeeTransferController::class)
    ->name('employee-transfers.')
    ->prefix('/employee-transfers/{employee_transfer}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
    });

// Warning
Route::controller(Action\WarningController::class)
    ->name('warnings.')
    ->prefix('/warnings/{warning}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::get('/print', 'printed')->name('print');
    });

// Attendance
Route::controller(Action\AttendanceController::class)
    ->name('attendances.')
    ->prefix('/attendances/{attendance}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/import', 'import')->name('import');
    });

// Transaction
Route::controller(Action\TransactionController::class)
    ->name('transactions.')
    ->prefix('/transactions/{transaction}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/subtract', 'subtract')->name('subtract');
        Route::post('/approve-and-subtract', 'approveAndSubtract')->name('approve_and_subtract');
        Route::post('/approve-and-add', 'approveAndAdd')->name('approve_and_add');
        Route::post('/add', 'add')->name('add');
        Route::get('/print', 'printed')->name('print');
        Route::get('/convert-to', 'convertTo')->name('convert_to');
        Route::get('/convert-from', 'convertFrom')->name('convert_from');
        Route::post('/update-status', 'updateStatus')->name('update_status');
    });

// Transaction Field
Route::controller(Action\TransactionFieldController::class)
    ->name('transaction-fields.')
    ->prefix('/transaction-fields/{transaction_field}')
    ->group(function () {
        Route::post('/subtract', 'subtract')->name('subtract');
        Route::post('/add', 'add')->name('add');
    });

// Sale
Route::controller(Action\SaleController::class)
    ->name('sales.')
    ->prefix('/sales/{sale}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/cancel', 'cancel')->name('cancel');
        Route::post('/convert-to-credit', 'convertToCredit')->name('convert_to_credit');
        Route::get('/print', 'printed')->name('print');
        Route::post('/subtract', 'subtract')->name('subtract');
        Route::post('/approve-and-subtract', 'approveAndSubtract')->name('approve_and_subtract');
        Route::post('/convert-to-siv', 'convertToSiv')->name('convert_to_siv');
    });

// Job Planner
Route::post('/job-planners/print', [Action\JobPlannerController::class, 'printed'])->name('job-planners.print');

// Leave
Route::controller(Action\LeaveController::class)
    ->name('leaves.')
    ->prefix('/leaves/{leaf}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/cancel', 'cancel')->name('cancel');
    });

// Advancement
Route::controller(Action\AdvancementController::class)
    ->name('advancements.')
    ->prefix('/advancements/{advancement}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
    });

// BOM
Route::controller(Action\BillOfMaterialController::class)
    ->name('bill-of-materials.')
    ->prefix('/bill-of-materials/{bill_of_material}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/import', 'import')->name('import');
    });

// Expense Claim
Route::controller(Action\ExpenseClaimController::class)
    ->name('expense-claims.')
    ->prefix('/expense-claims/{expense_claim}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/reject', 'reject')->name('reject');
    });

// Self-service Expense Claim
Route::controller(Action\ExpenseClaimController::class)
    ->name('expense-claims.request.')
    ->prefix('/expense-claims/request')
    ->group(function () {
        Route::get('/create', 'createExpenseClaim')->name('create');
        Route::post('/', 'storeExpenseClaim')->name('store');
    });

// Announcement
Route::get('/announcements/board', [Action\AnnouncementController::class, 'board'])->name('announcements.board');
Route::controller(Action\AnnouncementController::class)
    ->name('announcements.')
    ->prefix('/announcements/{announcement}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
    });

// Self-service Leave
Route::controller(Action\LeaveController::class)
    ->name('leaves.request.')
    ->prefix('/leaves/request')
    ->group(function () {
        Route::get('/create', 'createLeaveRequest')->name('create');
        Route::post('/', 'storeLeaveRequest')->name('store');
    });

// Compensation Adjustment
Route::controller(Action\CompensationAdjustmentController::class)
    ->name('compensation-adjustments.')
    ->prefix('/compensation-adjustments/{compensation_adjustment}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/cancel', 'cancel')->name('cancel');
    });

// Expense
Route::controller(Action\ExpenseController::class)
    ->name('expenses.')
    ->prefix('/expenses/{expense}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
    });

// Price
Route::controller(Action\PriceController::class)
    ->name('prices.')
    ->prefix('/prices/{price}')
    ->group(function () {
        Route::get('/toggle', 'toggle')->name('toggle');
    });

// Price Increment
Route::controller(Action\PriceIncrementController::class)
    ->name('price-increments.')
    ->prefix('/price-increments/{price_increment}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/import', 'import')->name('import');
    });

// Payroll
Route::controller(Action\PayrollController::class)
    ->name('payrolls.')
    ->prefix('/payrolls/{payroll}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/pay', 'pay')->name('pay');
        Route::get('/print', 'printed')->name('print');
    });

// Merchandise Batch
Route::controller(Action\MerchandiseBatchController::class)
    ->name('merchandise-batches.')
    ->prefix('/merchandise-batches/{merchandise_batch}')
    ->group(function () {
        Route::get('/convert-to-damage', 'convertToDamage')->name('convert_to_damage');
    });

// Customer Deposit
Route::controller(Action\CustomerDepositController::class)
    ->name('customer-deposits.')
    ->prefix('/customer-deposits/{customer_deposit}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
    });

// Cost Update
Route::controller(Action\CostUpdateController::class)
    ->name('cost-updates.')
    ->prefix('/cost-updates/{cost_update}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/reject', 'reject')->name('reject');
    });

//Exchange
Route::controller(Action\ExchangeController::class)
    ->name('exchanges.')
    ->prefix('/exchanges/{exchange}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::post('/execute', 'execute')->name('execute');
        Route::post('/approve-and-execute', 'approveAndExecute')->name('approve_and_execute');
        Route::get('/print', 'printed')->name('print');
    });
