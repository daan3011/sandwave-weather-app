<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WeatherMonitorController;
use App\Http\Controllers\WeatherReadingController;

Route::get('/weather', WeatherController::class);

Route::apiResource('weather-monitors', WeatherMonitorController::class)
->except(['update']);

Route::get('weather-readings', [WeatherReadingController::class, 'index']);
