<?php

use App\Http\Controllers\Action as Action;
use Illuminate\Support\Facades\Route;

// Permissions
Route::controller(Action\PermissionController::class)
    ->name('permissions.')
    ->prefix('/users/{employee}/permissions')
    ->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
    });

// Notifications
Route::controller(Action\NotificationController::class)
    ->name('notifications.')
    ->prefix('/notifications')
    ->group(function () {
        Route::patch('mark-all-read', 'markAllAsRead')->name('markAllAsRead');
        Route::post('delete-all', 'deleteAll')->name('delete_all');
    });


// Import
Route::post('/users/import', [Action\EmployeeController::class, 'import'])->name('users.import');

Route::post('/branches/import', [Action\BranchController::class, 'import'])->name('branches.import');

Route::post('/sections/import', [Action\SectionController::class, 'import'])->name('sections.import');

Route::post('/school-classes/import', [Action\SchoolClassController::class, 'import'])->name('classes.import');

Route::post('/vehicles/import', [Action\VehicleController::class, 'import'])->name('vehicles.import');

Route::post('/routes/import', [Action\RouteController::class, 'import'])->name('routes.import');

Route::post('/student-categories/import', [Action\StudentCategoryController::class, 'import'])->name('student-categories.import');

Route::post('/student-groups/import', [Action\StudentGroupController::class, 'import'])->name('student-groups.import');

Route::post('/designations/import', [Action\DesignationController::class, 'import'])->name('designations.import');

Route::post('/departments/import', [Action\DepartmentController::class, 'import'])->name('departments.import');

Route::post('/fee-groups/import', [Action\FeeGroupController::class, 'import'])->name('fee-groups.import');

Route::post('/fee-types/import', [Action\FeeTypeController::class, 'import'])->name('fee-types.import');

// Transaction
Route::controller(Action\TransactionController::class)
    ->name('transactions.')
    ->prefix('/transactions/{transaction}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::get('/print', 'printed')->name('print');
        Route::post('/update-status', 'updateStatus')->name('update_status');
    });
