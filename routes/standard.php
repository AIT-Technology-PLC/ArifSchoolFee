<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\WarehouseProductController;
use Illuminate\Support\Facades\Route;

// Route::get('/', 'HomeController@index')->name('home');

Route::get('/', [MenuController::class, 'index'])->name('home');

Route::get('/product/uom/{product}', [ProductController::class, 'getProductUOM']);

Route::get('/employees/{employee}/permissions/edit', [PermissionController::class, 'edit'])->name('permissions.edit');

Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);

Route::get('/notifications/{notification}/mark-as-read',
    [NotificationController::class, 'markNotificationAsRead'])
    ->name('notifications.markAsRead');

Route::get('/warehouses/{warehouse}/products/{product}', WarehouseProductController::class)
    ->name('warehouses-products')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Inventory History');

Route::get('merchandises', MerchandiseController::class)
    ->name('merchandises.index')
    ->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Merchandise Inventory Level');

Route::patch('/employees/{employee}/permissions', [PermissionController::class, 'update'])->name('permissions.update');

Route::patch('/notifications/mark-all-read',
    [NotificationController::class, 'markAllNotificationsAsRead'])
    ->name('notifications.markAllAsRead');

Route::resource('products', ProductController::class);

Route::resource('categories', ProductCategoryController::class);

Route::resource('employees', EmployeeController::class);

Route::resource('companies', CompanyController::class);

Route::resource('purchases', PurchaseController::class);

Route::resource('sales', SaleController::class);

Route::resource('prices', PriceController::class);

Route::resource('notifications', NotificationController::class)->only("index");
