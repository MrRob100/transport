<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusTimesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/bus-times', [BusTimesController::class, 'getBusTimes']);
Route::get('/train-times', [BusTimesController::class, 'getTrainTimes']);