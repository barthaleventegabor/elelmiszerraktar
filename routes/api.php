<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\SupplierController;
use App\Models\User;

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

    // Category
    Route::get('/categories', [CategoryController::class, 'getCategories']);
    Route::get('/category/{category}', [CategoryController::class, 'getCategory']);
    Route::post('/createcategory', [CategoryController::class, 'create']);
    Route::put('/updatecategory/{category}', [CategoryController::class, 'update']);
    Route::delete('/deletecategory/{category}', [CategoryController::class, 'delete']);

    // Supplier
    Route::get('/suppliers', [SupplierController::class, 'getSuppliers']);
    Route::get('/supplier/{supplier}', [SupplierController::class, 'getSupplier']);
    Route::post('/createsupplier', [SupplierController::class, 'create']);
    Route::put('/updatesupplier/{supplier}', [SupplierController::class, 'update']);
    Route::delete('/deletesupplier/{supplier}', [SupplierController::class, 'delete']);

    // User
    Route::post('/logout', [UserController::class, 'logout']);
    Route::put('/setpassword', [UserController::class, 'setPassword']);

    Route::get('/getprofile', [UserController::class, 'getProfile']);
    Route::put('/updateprofile', [UserController::class, 'updateProfile']);

    // Admin
    Route::put('/makeadmin/{user}', [UserController::class, 'makeAdmin']);
    Route::put('/removeadmin/{user}', [UserController::class, 'removeAdmin']);
    Route::delete('/deleteuser/{user}', [UserController::class, 'deleteUser']);
    Route::get('/users', [UserController::class, 'getProfiles']);
    Route::put('/setpasswordbyadmin/{user}', [UserController::class, 'setPasswordByAdmin']);
    Route::put('/updateprofilebyadmin/{user}', [UserController::class, 'updateProfileByAdmin']);

    
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