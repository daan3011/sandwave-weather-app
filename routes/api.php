<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WeatherMonitorController;
use App\Http\Controllers\WeatherReadingController;

Route::middleware(['throttle:weather'])->get('/weather', [WeatherController::class, 'index']);

Route::middleware(['throttle:weather-monitors'])->apiResource('weather-monitors', WeatherMonitorController::class)
    ->except(['update']);

Route::middleware(['throttle:weather-readings'])->get('weather-readings', [WeatherReadingController::class, 'index']);
