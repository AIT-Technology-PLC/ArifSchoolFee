<?php

use App\Http\Controllers\Resource as Resource;
use Illuminate\Support\Facades\Route;

Route::resource('employees', Resource\EmployeeController::class);

Route::resource('companies', Resource\CompanyController::class)->only(['edit', 'update']);

Route::resource('notifications', Resource\NotificationController::class)->except(['create', 'store', 'edit']);

Route::resource('warehouses', Resource\WarehouseController::class)->except(['show', 'destroy']);

Route::resource('transaction-fields', Resource\TransactionFieldController::class)->only(['destroy']);