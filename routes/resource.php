<?php

use App\Http\Controllers\Resource as Resource;
use Illuminate\Support\Facades\Route;

Route::resource('users', Resource\EmployeeController::class);

Route::resource('login-permissions', Resource\LoginPermissionController::class)->only('index');

Route::resource('schools', Resource\CompanyController::class)->only(['edit', 'update']);

Route::resource('notifications', Resource\NotificationController::class)->except(['create', 'store', 'edit']);

Route::resource('branches', Resource\BranchController::class)->except(['show', 'destroy']);

Route::resource('academic-years', Resource\AcademicYearController::class)->except(['show']);

Route::resource('sections', Resource\SectionController::class)->except(['show']);

Route::resource('school-classes', Resource\SchoolClassController::class)->except(['show']);

Route::resource('fee-groups', Resource\FeeGroupController::class)->except(['show']);

Route::resource('fee-types', Resource\FeeTypeController::class)->except(['show']);

Route::resource('fee-discounts', Resource\FeeDiscountController::class)->except(['show']);

Route::resource('fee-masters', Resource\FeeMasterController::class)->except(['show']);

Route::resource('vehicles', Resource\VehicleController::class)->except(['show']);

Route::resource('routes', Resource\RouteController::class)->except(['show']);

Route::resource('designations', Resource\DesignationController::class)->except(['show']);

Route::resource('departments', Resource\DepartmentController::class)->except(['show']);

Route::resource('staff-directories', Resource\StaffDirectoryController::class)->except(['show']);

Route::resource('student-categories', Resource\StudentCategoryController::class)->except(['show']);

Route::resource('student-groups', Resource\StudentGroupController::class)->except(['show']);

Route::resource('student-directories', Resource\StudentGroupController::class)->except(['show']);

Route::resource('user-logs', Resource\UserLogController::class)->only(['index']);

Route::resource('activity-logs', Resource\ActivityLogController::class)->only(['index']);

Route::resource('transaction-fields', Resource\TransactionFieldController::class)->only(['destroy']);