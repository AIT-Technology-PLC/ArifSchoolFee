<?php

Route::get('/merchandises/level/warehouse/{warehouse}', 'MerchandiseInventoryLevelController@getCurrentMerchandiseLevelByWarehouse');

Route::post('/transfers/{transfer}/transfer', 'TransferController@transfer')->name('transfers.transfer');

Route::post('purchase-orders/{purchaseOrder}/close', 'PurchaseOrderController@close')->name('purchase-orders.close');

Route::resource('suppliers', 'SupplierController');

Route::resource('warehouses', 'WarehouseController');

Route::resource('customers', 'CustomerController');

Route::post('/gdns/{gdn}/approve', 'GdnController@approve')->name('gdns.approve');

Route::resource('gdns', 'GdnController');

Route::resource('transfers', 'TransferController');

Route::resource('purchase-orders', 'PurchaseOrderController');

Route::resource('grns', 'GrnController');
