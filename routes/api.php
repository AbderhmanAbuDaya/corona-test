<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('auth/tokens/new',[\App\Http\Controllers\Api\AccessTokensController::class,'register']);

Route::post('auth/tokens',[\App\Http\Controllers\Api\AccessTokensController::class,'store']);
Route::get('auth/tokens/user',[\App\Http\Controllers\Api\AccessTokensController::class,'getUser'])->middleware('auth:sanctum');
Route::delete('auth/tokens',[\App\Http\Controllers\Api\AccessTokensController::class,'destroy'])->middleware('auth:sanctum');
Route::post('auth/user/edit',[\App\Http\Controllers\Api\AccessTokensController::class,'editUser'])->middleware('auth:sanctum');


Route::post('change/status',[\App\Http\Controllers\Api\CoronaController::class,'changeStatus'])->middleware('auth:sanctum');
Route::post('save/location',[\App\Http\Controllers\Api\UserLocationController::class,'newLocation'])->middleware('auth:sanctum');
