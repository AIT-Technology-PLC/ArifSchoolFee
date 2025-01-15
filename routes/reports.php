<?php

use App\Http\Controllers\Report as Report;
use Illuminate\Support\Facades\Route;

Route::name('reports.')
    ->prefix('/reports')
    ->group(function () {
        Route::get('/students', [Report\StudentReportController::class, 'index'])->name('student');
        Route::get('/student-history', [Report\StudentHistoryReportController::class, 'index'])->name('student-history');
        Route::get('/staff', [Report\StaffReportController::class, 'index'])->name('staff');
    });
