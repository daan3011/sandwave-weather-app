<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WeatherMonitorController;

Route::get('/weather', WeatherController::class);
Route::apiResource('weather-monitors', WeatherMonitorController::class);


