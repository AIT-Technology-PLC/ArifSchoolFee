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

// Notifications
Route::controller(Action\NotificationController::class)
    ->name('notifications.')
    ->prefix('/notifications')
    ->group(function () {
        Route::patch('mark-all-read', 'markAllAsRead')->name('markAllAsRead');
        Route::post('delete-all', 'deleteAll')->name('delete_all');
    });


// Import
Route::post('/employees/import', [Action\EmployeeController::class, 'import'])->name('employees.import');
Route::post('/warehouses/import', [Action\WarehouseController::class, 'import'])->name('warehouses.import');

// Transaction
Route::controller(Action\TransactionController::class)
    ->name('transactions.')
    ->prefix('/transactions/{transaction}')
    ->group(function () {
        Route::post('/approve', 'approve')->name('approve');
        Route::get('/print', 'printed')->name('print');
        Route::post('/update-status', 'updateStatus')->name('update_status');
    });
