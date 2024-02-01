<?php

use Illuminate\Support\Facades\Route;
use TTBooking\FiscalRegistrar\Http\Controllers\FiscalRegistrarController;
use TTBooking\FiscalRegistrar\Http\Controllers\HomeController;
use TTBooking\FiscalRegistrar\Http\Controllers\ReceiptController;

Route::prefix('api/v1')->group(function () {

    Route::get('/connection', [FiscalRegistrarController::class, 'connections'])->name('fiscal-registrar.connections');

    Route::prefix('connection/{connection}')->group(function () {
        Route::post('/{operation}/{externalId}', [FiscalRegistrarController::class, 'register'])->name('fiscal-registrar.register');
        Route::get('/report/{id}', [FiscalRegistrarController::class, 'report'])->name('fiscal-registrar.report');
    });

    Route::apiResource('receipts', ReceiptController::class)->names([
        'index' => 'fiscal-registrar.receipts.index',
        'store' => 'fiscal-registrar.receipts.store',
        'show' => 'fiscal-registrar.receipts.show',
        'update' => 'fiscal-registrar.receipts.update',
        'destroy' => 'fiscal-registrar.receipts.destroy',
    ]);

    Route::prefix('receipts/{receipt}')->group(function () {
        Route::get('/preview', [ReceiptController::class, 'preview'])->name('fiscal-registrar.receipts.preview');
        Route::post('/register', [ReceiptController::class, 'register'])->name('fiscal-registrar.receipts.register');
        Route::get('/report', [ReceiptController::class, 'report'])->name('fiscal-registrar.receipts.report');
    });

});

// Catch-all Route...
Route::get('/{view?}', [HomeController::class, 'index'])->where('view', '(.*)')->name('fiscal-registrar.index');
