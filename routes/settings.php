<?php

Route::get('/products/create', function () {
    return view('products.create');
});

Route::resource('categories', 'ProductCategoryController');

Route::resource('employees', 'EmployeeController');

Route::resource('permissions', 'PermissionController');

Route::resource('companies', 'CompanyController');

Route::get('/permission-denied', 'ErrorPageController@getPermissionDeniedPage');

Route::get('/home', 'HomeController@index')->name('home');
