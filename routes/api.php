<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\UserController;

Route::post( "/login", [ UserController::class, "login" ]);
Route::post( "/register", [ UserController::class, "register" ]);
Route::middleware([ "auth:sanctum" ])->group( function() {
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::post('/logout', [UserController::class, 'logout']);
    
});

Route::get( "/verify_email/{id}/{hash}", function( Request $request, $id, $hash ){

    $user = User::findOrFail( $request->id );

    if( $user->hasVerifiedEmail() ) {

        return response()->json([ "message" => "Ez az email már meg van erősítve." ]);

    }

    $user->markEmailAsVerified();

    return response()->json([ "message" => "Sikeres megerősítés" ]);
})->name( "verification.verify" )->middleware( "signed" );