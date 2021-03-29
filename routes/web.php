<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {

    Route::prefix('connection/{connection}')->group(function () {
        // Registry Routes...
        Route::post('/sell', 'FiscalRegistrarController@sell')->name('fiscal-registrar.sell');
        Route::post('/sell-refund', 'FiscalRegistrarController@sellRefund')->name('fiscal-registrar.sell-refund');
        Route::post('/buy', 'FiscalRegistrarController@buy')->name('fiscal-registrar.buy');
        Route::post('/buy-refund', 'FiscalRegistrarController@buyRefund')->name('fiscal-registrar.buy-refund');

        // Report Routes...
        Route::get('/list', 'FiscalRegistrarController@list')->name('fiscal-registrar.list');
        Route::get('/report/{id}', 'FiscalRegistrarController@report')->name('fiscal-registrar.report');
        Route::post('/callback', 'FiscalRegistrarController@callback')->name('fiscal-registrar.callback');
    });

    Route::apiResource('receipts', 'ReceiptController')->names([
        'index' => 'fiscal-registrar.receipts.index',
        'store' => 'fiscal-registrar.receipts.store',
        'show' => 'fiscal-registrar.receipts.show',
        'update' => 'fiscal-registrar.receipts.update',
        'destroy' => 'fiscal-registrar.receipts.destroy',
    ]);

});

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('fiscal-registrar.index');
