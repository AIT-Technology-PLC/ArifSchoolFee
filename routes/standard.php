<?php

Route::post('merchandises/add-to-inventory/{purchase}',
    'MerchandiseInventoryTransactionController@addToInventory')
    ->name('merchandises.addToInventory');

Route::post('merchandises/subtract-from-inventory/{sale}',
    'MerchandiseInventoryTransactionController@subtractFromInventory')
    ->name('merchandises.subtractFromInventory');

Route::get('merchandises/level', 'MerchandiseInventoryLevelController@index')
    ->name('merchandises.level');

// Route::get('/', 'HomeController@index')->name('home');

Route::get('/', 'MenuController@index')->name('home');

Route::get('/product/uom/{product}', 'ProductController@getProductUOM');

Route::resource('products', 'ProductController');

Route::resource('categories', 'ProductCategoryController');

Route::resource('employees', 'EmployeeController');

Route::resource('companies', 'CompanyController');

Route::resource('purchases', 'PurchaseController');

Route::resource('sales', 'SaleController');

Route::resource('merchandises', 'MerchandiseController');

Route::resource('prices', 'PriceController');

Route::get('/employees/{employee}/permissions/edit', 'PermissionController@edit')->name('permissions.edit');

Route::patch('/employees/{employee}/permissions', 'PermissionController@update')->name('permissions.update');

Route::get('/notifications/read', 'NotificationController@getReadNotifications');

Route::get('/notifications/unread', 'NotificationController@getUnreadNotifications');

Route::get('/notifications/{notification}/mark-as-read', 'NotificationController@markNotificationAsRead')->name('notifications.markAsRead');

Route::patch('/notifications/mark-all-read', 'NotificationController@markAllNotificationsAsRead')->name('notifications.markAllAsRead');

Route::resource('notifications', 'NotificationController')->only("index");
