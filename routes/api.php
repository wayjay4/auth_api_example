<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthapiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// auth register/login 'public' routes
Route::resource('/register', AuthapiController::class);
Route::post('/login', [AuthController::class, 'login']);

// auth logout 'private' route
Route::post('/logout', [AuthController::class, 'logout'])
->middleware('auth:sanctum');

// product 'private' route
Route::resource('/products', ProductController::class)
->middleware('auth:sanctum');