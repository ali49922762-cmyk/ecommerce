<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products/search', [ProductController::class, 'search']);

//Login , Register

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

//index , show

Route::get('/products',[ProductController::class,'index']);

Route::get('/products/{id}', [ProductController::class, 'show']);

//Add , Update , Delete


// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('add', [ProductController::class, 'store']);
// });

//  Route::post('/products', [ProductController::class, 'store']);

Route::middleware('auth:sanctum')
    ->post('/products', [ProductController::class, 'store']);

Route::post('/products/{id}', [ProductController::class, 'update']);

Route::delete('/products/{id}', [ProductController::class, 'delete']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart']); 
    Route::get('/cart', [CartController::class, 'showCart']);       
});



