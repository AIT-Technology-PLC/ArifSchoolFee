<?php

Route::get('/merchandises/level/warehouse/{warehouse}', 'MerchandiseInventoryLevelController@getCurrentMerchandiseLevelByWarehouse');

Route::post('purchase-orders/{purchaseOrder}/close', 'PurchaseOrderController@close')->name('purchase-orders.close');

Route::resource('suppliers', 'SupplierController');

Route::resource('warehouses', 'WarehouseController');

Route::resource('customers', 'CustomerController');

Route::post('/gdns/{gdn}/approve', 'GdnController@approve')->name('gdns.approve');

Route::resource('gdns', 'GdnController');

Route::post('/transfers/transfer/{transfer}', 'TransferController@transfer')->name('transfers.transfer');

Route::post('/transfers/approve/{transfer}', 'TransferController@approve')->name('transfers.approve');

Route::resource('transfers', 'TransferController');

Route::resource('purchase-orders', 'PurchaseOrderController');

Route::post('/grns/{grn}/approve', 'GrnController@approve')->name('grns.approve');

Route::resource('grns', 'GrnController');

Route::resource('general-tender-checklists', 'GeneralTenderChecklistController');

Route::resource('tenders', 'TenderController');

Route::resource('tender-checklists', 'TenderChecklistController');