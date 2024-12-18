<?php

use App\Http\Controllers\CallCenter as CallCenter;
use Illuminate\Support\Facades\Route;

Route::get('/index', CallCenter\DashboardController::class)->name('index');

Route::resource('collect-fees', CallCenter\CollectFeeController::class)->only(['index','show']);