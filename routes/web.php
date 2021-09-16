<?php

use App\Http\Controllers\Auth as Auth;
use Illuminate\Support\Facades\Route;

Route::view('/offline', 'offline.index');

Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [Auth\RegisterController::class, 'register']);

Route::get('/login', [Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/auth/login', [Auth\LoginController::class, 'login'])->name('post.login');

Route::get('/password/confirm', [Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('/password/confirm', [Auth\ConfirmPasswordController::class, 'confirm']);

Route::post('logout', [Auth\LoginController::class, 'logout'])->name('logout');
