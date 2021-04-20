<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\MerchandiseInventoryLevelController;
use App\Http\Controllers\MerchandiseInventoryTransactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::post('merchandises/add-to-inventory/{purchase}',
    [MerchandiseInventoryTransactionController::class, 'addToInventory'])
    ->name('merchandises.addToInventory');

Route::post('merchandises/subtract-from-inventory/{sale}',
    [MerchandiseInventoryTransactionController::class, 'subtractFromInventory'])
    ->name('merchandises.subtractFromInventory');

Route::get('merchandises/level', [MerchandiseInventoryLevelController::class, 'index'])
    ->name('merchandises.level');

// Route::get('/', 'HomeController@index')->name('home');

Route::get('/', [MenuController::class, 'index'])->name('home');

Route::get('/product/uom/{product}', [ProductController::class, 'getProductUOM']);

Route::resource('products', ProductController::class);

Route::resource('categories', ProductCategoryController::class);

Route::resource('employees', EmployeeController::class);

Route::resource('companies', CompanyController::class);

Route::resource('purchases', PurchaseController::class);

Route::resource('sales', SaleController::class);

Route::resource('merchandises', MerchandiseController::class);

Route::resource('prices', PriceController::class);

Route::get('/employees/{employee}/permissions/edit', [PermissionController::class, 'edit'])->name('permissions.edit');

Route::patch('/employees/{employee}/permissions', [PermissionController::class, 'update'])->name('permissions.update');

Route::get('/notifications/read', [NotificationController::class, 'getReadNotifications']);

Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications']);

Route::get('/notifications/{notification}/mark-as-read',
    [NotificationController::class, 'markNotificationAsRead'])
    ->name('notifications.markAsRead');

Route::patch('/notifications/mark-all-read',
    [NotificationController::class, 'markAllNotificationsAsRead'])
    ->name('notifications.markAllAsRead');

Route::resource('notifications', NotificationController::class)->only("index");
