<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Api\V1\Authentication\app\Http\Controllers\AuthenticationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/')->name('v1.')->group(function () {
    Route::post('register' , [AuthenticationController::class , 'register'])->name('register');
    Route::post('login_sms' , [AuthenticationController::class , 'loginSms'])->name('login_sms');
    Route::post('verify_code' , [AuthenticationController::class , 'verifyCode'])->name('verify_code');
    Route::post('login' , [AuthenticationController::class , 'login']);
    Route::post('logout' , [AuthenticationController::class , 'logout'])->middleware('auth:sanctum');
});