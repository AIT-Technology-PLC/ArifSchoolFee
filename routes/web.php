<?php

use App\Http\Controllers\Auth as Auth;
use Illuminate\Support\Facades\Route;

Route::view('/offline', 'offline.index');

Route::controller(Auth\LoginController::class)
    ->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/auth/login', 'login')->name('post.login');
        Route::post('/logout', 'logout')->name('logout');
    });

// Confirm Password
Route::controller(Auth\ConfirmPasswordController::class)
    ->prefix('/password/confirm')
    ->group(function () {
        Route::get('/', 'showConfirmForm')->name('password.confirm');
        Route::post('/', 'confirm');
    });

// Change Password
Route::controller(Auth\PasswordResetController::class)
    ->middleware(['auth', 'isEmployeeEnabled'])
    ->name('password.')
    ->prefix('/password')
    ->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/update', 'update')->name('update');
    });

// Forget Password
Route::prefix('/password')->group(function () {
    Route::get('/recover', [Auth\ForgetPasswordController::class, 'index'])->name('forget.index');
    Route::get('/reset', [Auth\ForgetPasswordController::class, 'edit'])->name('forget.edit');
    Route::post('/request', [Auth\ForgetPasswordController::class, 'update'])->name('forget.update');
});