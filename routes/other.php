<?php

use App\Http\Controllers\Api as Api;
use App\Http\Controllers\Invokable as Invokable;
use Illuminate\Support\Facades\Route;

Route::view('/', 'menu.index')->name('home');

Route::prefix('api')
    ->name('api.')
    ->group(function () {
        Route::get('/products/{category}/by-category',
            [Api\ProductController::class, 'getproductsByCategory']);

        Route::apiResource('products', Api\ProductController::class)->only('index', 'show');
        Route::apiResource('bill-of-materials', Api\BillOfMaterialController::class)->only('index');
    });

Route::get('/history/products/{product}/warehouses/{warehouse}',
    Invokable\ProductPerWarehouseHistoryController::class)
    ->name('warehouses-products');

Route::get('/merchandises/{type}',
    Invokable\MerchandiseInventoryLevelController::class)
    ->name('merchandises.index')
    ->where('type', 'on-hand|available|wip|reserved|out-of-stock');

Route::get('/warehouses/{warehouse}/merchandises',
    Invokable\MerchandiseInventoryLevelByWarehouseController::class)
    ->name('warehouses.merchandises');
