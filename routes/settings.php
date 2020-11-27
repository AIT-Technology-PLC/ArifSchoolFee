<?php

Route::resource('products', 'ProductController');

Route::get('/product/uom/{product}', 'ProductController@getProductUOM');

Route::resource('categories', 'ProductCategoryController');

Route::resource('employees', 'EmployeeController');

Route::resource('permissions', 'PermissionController');

Route::resource('companies', 'CompanyController');

Route::resource('suppliers', 'SupplierController');

Route::resource('purchases', 'PurchaseController');

Route::get('/permission-denied', 'ErrorPageController@getPermissionDeniedPage');

Route::get('/home', 'HomeController@index')->name('home');
