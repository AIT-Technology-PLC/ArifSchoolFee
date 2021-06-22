<?php

use App\Http\Controllers\Auth as Auth;
use App\Http\Controllers\OfflineController;
use Illuminate\Support\Facades\Route;

Route::get('/offline', [OfflineController::class, 'offline']);

Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [Auth\RegisterController::class, 'register']);

Route::get('/login', [Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/auth/login', [Auth\LoginController::class, 'login'])->name('post.login');

Route::post('logout', [Auth\LoginController::class, 'logout'])->name('logout');
