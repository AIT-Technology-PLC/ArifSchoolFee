<?php

use App\Http\Controllers\Auth as Auth;
use Illuminate\Support\Facades\Route;

Route::view('/offline', 'offline.index');

// // Sign up
// Route::get('/register',
//     [Auth\RegisterController::class, 'showRegistrationForm'])
//     ->name('register');

// Route::post('/register',
//     [Auth\RegisterController::class, 'register']);

// Login
Route::get('/login',
    [Auth\LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/auth/login',
    [Auth\LoginController::class, 'login'])
    ->name('post.login');

Route::post('/logout',
    [Auth\LoginController::class, 'logout'])
    ->name('logout');

// Confirm Password
Route::get('/password/confirm',
    [Auth\ConfirmPasswordController::class, 'showConfirmForm'])
    ->name('password.confirm');

Route::post('/password/confirm',
    [Auth\ConfirmPasswordController::class, 'confirm']);

// Change Password
Route::middleware(['auth', 'isEmployeeEnabled'])->group(function () {
    Route::get('/password/edit',
        [Auth\PasswordResetController::class, 'edit'])
        ->name('password.edit');

    Route::patch('/password/update',
        [Auth\PasswordResetController::class, 'update'])
        ->name('password.update');
});
