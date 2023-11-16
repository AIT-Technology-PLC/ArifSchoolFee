<?php

use App\Http\Controllers\Admin as Admin;
use Illuminate\Support\Facades\Route;

Route::post('companies/{company}/reset', Admin\CompanyResetAccountController::class)->name('companies.reset');
Route::post('companies/{company}/features', Admin\CompanyFeatureController::class)->name('companies.features.update');
Route::post('companies/{company}/integrations', Admin\CompanyIntegrationController::class)->name('companies.integrations.update');
Route::post('companies/{company}/limits', Admin\CompanyLimitController::class)->name('companies.limits.update');
Route::post('companies/{company}/toggle', Admin\CompanyToggleActivationController::class)->name('companies.toggle_activation');
Route::resource('companies', Admin\CompanyController::class);
Route::resource('users', Admin\UserController::class)->except(['show', 'destroy']);
Route::resource('companies.pads', Admin\CompanyPadController::class)->shallow()->except(['index']);
Route::resource('pad-fields', Admin\PadFieldController::class)->only(['destroy']);