<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/{connection}')->group(function () {

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
