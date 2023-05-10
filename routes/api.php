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
    Route::get("/index", [KendaraanController::class, 'index'])->name('index');
    Route::post("/store", [KendaraanController::class, 'store'])->name('store');
    Route::put("/update/{id}", [KendaraanController::class, 'update'])->name('update');
    Route::get("/show/{id}", [KendaraanController::class, 'show'])->name('show');
    Route::delete("/delete/{id}", [KendaraanController::class, 'destroy'])->name('destroy');
    Route::get("/report/{start}/{end}", [KendaraanController::class, 'report'])->name('report');
  });
});

Route::fallback(function(){
  return response()->json(['message' => 'Page Not Found'], 404);
});
