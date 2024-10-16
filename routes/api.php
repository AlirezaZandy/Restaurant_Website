<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Api\V1\Authentication\app\Http\Controllers\AuthenticationController;
use Modules\Api\V1\FoodType\app\Http\Controllers\FoodTypeController;
use Modules\Api\V1\MaterialType\app\Http\Controllers\MaterialTypeController;
use Modules\Api\V1\Material\app\Http\Controllers\MaterialController;
use Modules\Api\V1\Order\app\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/')->name('v1.')->group(function () {
    Route::apiResource('foodtypes' , FoodTypeController::class);
    Route::apiResource('materialtypes' , MaterialTypeController::class);
    Route::apiResource('materials' , MaterialController::class);
    Route::post('order/{foodType}/{step}' , [OrderController::class , 'provideMaterials'])->name('provideMaterials');
    Route::post('register' , [AuthenticationController::class , 'register'])->name('register');
    Route::post('login' , [AuthenticationController::class , 'login']);
    Route::post('logout' , [AuthenticationController::class , 'logout'])->middleware('auth:sanctum');
//    Route::post('login_sms' , [AuthenticationController::class , 'loginSms'])->name('login_sms');
//    Route::post('verify_code' , [AuthenticationController::class , 'verifyCode'])->name('verify_code');
});
