<?php

use App\Http\Controllers\Api as Api;
use App\Http\Controllers\Invokable as Invokable;
use App\Http\Controllers\Resource as Resource;
use Illuminate\Support\Facades\Route;

Route::get('/', Invokable\HomeController::class)->name('home');

Route::post('/employees/{employee}/toggle', Invokable\ToggleEmployeeController::class)->name('employees.toggle');

Route::post('/users/{user}/toggle', Invokable\ToggleUserController::class)->name('users.toggle');

Route::prefix('api')
    ->name('api.')
    ->group(function () {
        Route::get('/my-company',
            [Api\CompanyController::class, 'show']);
    });

// Push Notification
Route::post('subscriptions', [Resource\WebNotificationController::class, 'update']);

Route::post('subscriptions/delete', [Resource\WebNotificationController::class, 'destroy']);

// Manifest file (optional if VAPID is used)
Route::get('manifest.json', function () {
    return [
        'name' => config('app.name'),
        'gcm_sender_id' => config('webpush.gcm.sender_id'),
    ];
});
