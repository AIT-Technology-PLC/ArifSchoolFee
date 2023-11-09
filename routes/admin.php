<?php

use App\Http\Controllers\Admin as Admin;
use Illuminate\Support\Facades\Route;

Route::post('companies/{company}/integrations', Admin\CompanyIntegrationController::class)->name('companies.integrations.update');
Route::post('companies/{company}/limits', Admin\CompanyLimitController::class)->name('companies.limits.update');
Route::post('companies/{company}/toggle', Admin\CompanyToggleActivationController::class)->name('companies.toggle_activation');
Route::resource('companies', Admin\CompanyController::class);
