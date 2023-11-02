<?php

use App\Http\Controllers\Admin as Admin;
use Illuminate\Support\Facades\Route;

Route::resource('companies', Admin\CompanyController::class);
