<?php

use App\Http\Controllers\ServiceCenter as ServiceCenter;
use Illuminate\Support\Facades\Route;

Route::get('/index', ServiceCenter\DashboardController::class)->name('index');

Route::resource('collect-fees', ServiceCenter\CollectFeeController::class)->only(['index','show']);