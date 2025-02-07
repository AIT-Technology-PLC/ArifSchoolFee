<?php

use App\Http\Controllers\Auth as Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Other as Other;

Route::view('/offline', 'offline.index');

Route::controller(Auth\LoginController::class)
    ->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::get('/admin/login', 'showAdminLoginForm')->name('admin.login');
        Route::get('/bank/login', 'showBankLoginForm')->name('bank.login');
        Route::get('/call-center/login', 'showCallCenterLoginForm')->name('callcenter.login');
        
        Route::post('/auth/login', 'login')->name('post.login');
        Route::post('/admin/auth/login', 'login')->name('post.admin.login');
        Route::post('/bank/auth/login', 'login')->name('post.bank.login');
        Route::post('/call-center/auth/login', 'login')->name('post.callcenter.login');

        Route::post('/logout', 'logout')->name('logout');
        Route::post('/admin/logout', 'logout')->name('admin.logout');
        Route::post('/bank/logout', 'logout')->name('bank.logout');
        Route::post('/callcenter/logout', 'logout')->name('callcenter.logout');
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

// School Or Company Registration
Route::controller(Other\CompanyController::class)
    ->prefix('/schools')
    ->group(function () {
        Route::get('/register', 'register')->name('schools.register');
        Route::post('/store', 'store')->name('schools.store');
    });

// ArifPay
Route::controller(Other\ArifPayController::class)
    ->prefix('/arifpay')
    ->group(function () {
        Route::get('/cancel/{routeId}', 'cancelSession')->name('arifpay.cancel');
        Route::get('/success/{routeId}', 'successSession')->name('arifpay.success');
        Route::get('/error/{routeId}', 'errorSession')->name('arifpay.error');
        Route::post('/callback', 'callback')->name('arifpay.callback');
    });

// Telebirr
Route::controller(Other\TelebirrPaymentController::class)
    ->name('telebirr.')
    ->prefix('/telebirr')
    ->group(function () {
        Route::get('/redirect/{routeId}', 'redirect')->name('redirect');
        Route::post('/notify', 'notify')->name('notify');
    });