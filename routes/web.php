<?php

use App\Enums\FileType;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProcessTransactionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\SomeOtherMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/transfer', [TransferController::class, 'create'])->name('transfer.create');
Route::post('/transfer', [TransferController::class, 'store'])->name('transfer.store');


// Visit/transfer ->GET/transfer
// Fill form & submit -> POST /transfer






















































Route::prefix('/administration')->middleware([CheckUserRole::class, SomeOtherMiddleware::class])->group(function () {


Route::get('/', function () {
   return 'Secret Admin Page';

});

Route::get('/other' , function () {
   return 'Another Admin Page';

    })->withoutMiddleware(SomeOtherMiddleware::class);
});













































//Route::get('/report/{fileType}', function(Request $request, FileType $fileType){
//    $year = $request->get('year');
//    $month = $request->get('month');
//
//    return 'Generating'.$fileType->value . 'report ' . ' for ' . $year . ' and ' . $month;
//});





























