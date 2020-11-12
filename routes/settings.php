<?php

Route::get('/categories/create', function () {
    return view('categories.create');
});

Route::get('/products/create', function () {
    return view('products.create');
});

Route::resource('employees', 'EmployeeController');

Route::get('/home', 'HomeController@index')->name('home');
