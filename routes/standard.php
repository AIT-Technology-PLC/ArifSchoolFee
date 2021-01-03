<?php

Route::resource('products', 'ProductController');

Route::get('/product/uom/{product}', 'ProductController@getProductUOM');

Route::resource('categories', 'ProductCategoryController');

Route::resource('employees', 'EmployeeController');

Route::resource('permissions', 'PermissionController');

Route::resource('companies', 'CompanyController');

Route::resource('purchases', 'PurchaseController');

Route::resource('sales', 'SaleController');

Route::get('merchandises/level', 'MerchandiseController@showCurrentInventoryLevelByProducts')->name('merchandises.level');
Route::post('merchandises/add-to-inventory/{purchase}', 'MerchandiseController@addToInventory')
    ->name('merchandises.addToInventory');
Route::post('merchandises/subtract-from-inventory/{sale}', 'MerchandiseController@subtractFromInventory')
    ->name('merchandises.subtractFromInventory');
Route::resource('merchandises', 'MerchandiseController');

Route::get('/permission-denied', 'ErrorPageController@getPermissionDeniedPage');

Route::get('/home', 'HomeController@index')->name('home');
