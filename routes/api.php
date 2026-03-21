<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusTimesController;
use App\Http\Controllers\TestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', [TestController::class, 'test']);

Route::get('/bus-times', [BusTimesController::class, 'getBusTimes']);
Route::get('/train-times', [BusTimesController::class, 'getTrainTimes']);