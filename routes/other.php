<?php

use App\Http\Controllers\Api as Api;
use App\Http\Controllers\Invokable as Invokable;
use Illuminate\Support\Facades\Route;

Route::view('/', 'menu.index')->name('home');

Route::prefix('api')->group(function(){

    Route::get('/notifications/unread',
        [Api\NotificationController::class, 'unread']);
    
    Route::get('/products/{product}/unit-of-measurement',
        [Api\ProductController::class, 'getProductUOM']);

});

Route::get('/history/products/{product}/warehouses/{warehouse}',
    Invokable\ProductPerWarehouseHistoryController::class)
    ->name('warehouses-products');

Route::get('/merchandises/{type}',
    Invokable\MerchandiseInventoryLevelController::class)
    ->name('merchandises.index')
    ->where('type', 'on-hand|available|reserved|out-of-stock');

Route::get('/warehouses/{warehouse}/merchandises',
    Invokable\MerchandiseInventoryLevelByWarehouseController::class)
    ->name('warehouses.merchandises');
