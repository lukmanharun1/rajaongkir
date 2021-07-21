<?php

use App\Http\Controllers\Province;
use App\Http\Controllers\ProvinceController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/province', [ProvinceController::class, 'index']);
Route::post('/province', [ProvinceController::class, 'store']);
Route::get('/province/{province_id:province_id}', [ProvinceController::class, 'show']);
Route::put('/province/{id}', [ProvinceController::class, 'update']);
Route::delete('/province/{id}', [ProvinceController::class, 'destroy']);
