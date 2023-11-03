<?php

use App\Http\Controllers\Admin as Admin;
use Illuminate\Support\Facades\Route;

Route::post('companies/{company}/toggle', Admin\CompanyToggleActivationController::class)->name('companies.toggle_activation');
Route::resource('companies', Admin\CompanyController::class);
