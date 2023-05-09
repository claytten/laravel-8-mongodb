<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KendaraanController;
use App\Http\Controllers\API\PenjualanController;
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

Route::post('/login', [AuthController::class,'login'])->name('login');
Route::post('/signup', [AuthController::class,'signup'])->name('signup');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class,'logout'])->name('logout');
Route::middleware('auth:sanctum')->get('/me', [AuthController::class,'getAuthenticatedUser'])->name('me');


Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::group(['prefix' => 'kendaraan'], function () {
    Route::resource('kendaraan', KendaraanController::class)->only(['index', 'store', 'show']);
  });

  Route::group(['prefix' => 'penjualan'], function () {
    Route::post("/store", [PenjualanController::class, 'store'])->name('kendaraan.store');
  });
});

Route::fallback(function(){
  return response()->json(['message' => 'Page Not Found'], 404);
});
