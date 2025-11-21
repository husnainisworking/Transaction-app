<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users' , [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users' , [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

//why?
// PUT is http method for updating resources
// DELETE is for removing resources
// {id} captures which user to update/delete

//under the hood: laravel's routes maps HTTP methods(GET,POST,PUT,DELETE) to different
// controller methods, following REST conventions.
