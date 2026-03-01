<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\UserController;

// User
Route::post( "/login", [ UserController::class, "login" ]);
Route::post( "/register", [ UserController::class, "register" ]);

// Auth
Route::middleware([ "auth:sanctum" ])->group( function() {
    // Product
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::get('/product/{product}', [ProductController::class, 'getProduct']);
    Route::post('/createproduct', [ProductController::class, 'create']);
    Route::put('/updateproduct/{product}', [ProductController::class, 'update']);
    Route::delete('/deleteproduct/{product}', [ProductController::class, 'delete']);

    // User
    Route::post('/logout', [UserController::class, 'logout']);

    
});


// Email
Route::get( "/verify_email/{id}/{hash}", function( Request $request, $id, $hash ){

    $user = User::findOrFail( $request->id );

    if( $user->hasVerifiedEmail() ) {

        return response()->json([ "message" => "Ez az email már meg van erősítve." ]);

    }

    $user->markEmailAsVerified();

    return response()->json([ "message" => "Sikeres megerősítés" ]);
})->name( "verification.verify" )->middleware( "signed" );