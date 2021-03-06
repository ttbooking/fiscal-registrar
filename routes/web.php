<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {

    Route::prefix('connection/{connection}')->group(function () {
        // Registry Routes...
        Route::post('/sell/{externalId}', 'FiscalRegistrarController@sell')->name('fiscal-registrar.sell');
        Route::post('/sell-refund/{externalId}', 'FiscalRegistrarController@sellRefund')->name('fiscal-registrar.sell-refund');
        Route::post('/buy/{externalId}', 'FiscalRegistrarController@buy')->name('fiscal-registrar.buy');
        Route::post('/buy-refund/{externalId}', 'FiscalRegistrarController@buyRefund')->name('fiscal-registrar.buy-refund');

        // Report Routes...
        Route::get('/report/{id}', 'FiscalRegistrarController@report')->name('fiscal-registrar.report');
        Route::post('/callback', 'FiscalRegistrarController@callback')->withoutMiddleware('auth')->name('fiscal-registrar.callback');
    });

    Route::apiResource('receipts', 'ReceiptController')->names([
        'index' => 'fiscal-registrar.receipts.index',
        'store' => 'fiscal-registrar.receipts.store',
        'show' => 'fiscal-registrar.receipts.show',
        'update' => 'fiscal-registrar.receipts.update',
        'destroy' => 'fiscal-registrar.receipts.destroy',
    ]);

    Route::prefix('receipts/{receipt}')->group(function () {
        Route::get('/print', 'ReceiptController@print')->name('fiscal-registrar.receipts.print');
        Route::post('/register', 'ReceiptController@register')->name('fiscal-registrar.receipts.register');
        Route::get('/report', 'ReceiptController@report')->name('fiscal-registrar.receipts.report');
    });

});

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('fiscal-registrar.index');
