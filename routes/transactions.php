<?php

use App\Http\Controllers\ProcessTransactionController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

    Route::controller(TransactionController::class)->group(function() {
        Route::get('/', 'index')->name('home');
        Route::get('/create', 'create')->name('create');
        Route::get('/{transactionId}/{fileId?}', 'show')->whereNumber('transactionId')->name('show');
        Route::post('/', 'store')->name('store');
        Route::get('/{transactionId}/documents', 'documents')->name('documents');
    });

    Route::get('/{transactionId}/process', ProcessTransactionController::class)->whereNumber('transactionId');


