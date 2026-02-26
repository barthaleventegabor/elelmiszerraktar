<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\UserController;

Route::post( "/login", [ UserController::class, "login" ]);
Route::middleware([ "auth:sanctum" ])->group( function() {
    Route::get('/products', [ProductController::class, 'getProducts']);
});