<?php

use App\Http\Controllers\Api as Api;
use App\Http\Controllers\Invokable as Invokable;
use Illuminate\Support\Facades\Route;

Route::get('/', Invokable\HomeController::class)->name('home');

Route::prefix('api')
    ->name('api.')
    ->group(function () {
        Route::middleware('auth:sanctum')
            ->withoutMiddleware(['auth', 'isEmployeeEnabled'])
            ->post('/sales/printed', [Api\SaleController::class, 'assignFSNumber']);

        Route::get('/products/{category}/by-category',
            [Api\ProductController::class, 'getproductsByCategory']);

        Route::get('/my-company',
            [Api\CompanyController::class, 'show']);

        Route::get('merchandises/products/{product}/warehouses/{warehouse?}',
            [Api\MerchandiseController::class, 'show']);

        Route::apiResource('products', Api\ProductController::class)->only('show');
        Route::apiResource('bill-of-materials', Api\BillOfMaterialController::class)->only('index');
        Route::apiResource('customers', Api\CustomerController::class)->only('index');
        Route::apiResource('suppliers', Api\SupplierController::class)->only('index');
        Route::apiResource('merchandise-batches', Api\MerchandiseBatchController::class)->only('index');
        Route::apiResource('compensations', Api\CompensationController::class)->only('index');
        Route::apiResource('gdns', Api\GdnController::class)->only('show');
        Route::apiResource('sales', Api\SaleController::class);
    });

Route::get('/history/products/{product}/warehouses/{warehouse}',
    Invokable\ProductPerWarehouseHistoryController::class)
    ->name('warehouses-products');

Route::get('/merchandises/{type}',
    Invokable\MerchandiseInventoryLevelController::class)
    ->name('merchandises.index')
    ->where('type', 'on-hand|available|wip|reserved|out-of-stock|expired');

Route::get('/warehouses/{warehouse}/merchandises',
    Invokable\MerchandiseInventoryLevelByWarehouseController::class)
    ->name('warehouses.merchandises');

Route::get('/payslips/payroll/{payroll}/employee/{employee}',
    Invokable\PayslipController::class)
    ->name('payslips.print');
