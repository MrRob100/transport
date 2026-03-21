<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/', [App\Http\Controllers\BusTimesController::class, 'index']);

Route::get('/playground', [TestController::class, 'playground']);
Route::post('/playground', [TestController::class, 'playgroundSubmit']);
