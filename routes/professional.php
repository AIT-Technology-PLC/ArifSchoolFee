<?php

Route::get('/merchandises/level/warehouse/{warehouse}', 'MerchandiseInventoryLevelController@getCurrentMerchandiseLevelByWarehouse');

Route::get('/purchases/{purchase}/sivs', 'SivController@getSivsOfPurchase')->name('purchases.sivs');

Route::get('/sales/{sale}/sivs', 'SivController@getSivsOfSale')->name('sales.sivs');

Route::resource('suppliers', 'SupplierController');

Route::resource('warehouses', 'WarehouseController');

Route::resource('customers', 'CustomerController');

Route::resource('sivs', 'SivController');
