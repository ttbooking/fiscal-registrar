<?php

use Illuminate\Support\Facades\Route;

Route::post('/api/v1/connection/{connection}/callback', 'FiscalRegistrarController@callback')->name('fiscal-registrar.callback');
