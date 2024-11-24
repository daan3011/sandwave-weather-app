<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    Route::middleware(['throttle:weather'])->get('/api/weather', function () {
        return response()->json(['message' => 'Weather endpoint accessed']);
    });

    Route::middleware(['throttle:weather-monitors'])->get('/api/weather-monitors', function () {
        return response()->json(['message' => 'Weather Monitors endpoint accessed']);
    });

    Route::middleware(['throttle:weather-readings'])->get('/api/weather-readings', function () {
        return response()->json(['message' => 'Weather Readings endpoint accessed']);
    });
});

test('weather endpoint rate limiting works', function () {
    for ($i = 0; $i < 15; $i++) {
        $response = $this->getJson('/api/weather');
        $response->assertStatus(Response::HTTP_OK);
    }

    $response = $this->getJson('/api/weather');
    $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
});

test('weather-monitors endpoint rate limiting works', function () {
    for ($i = 0; $i < 50; $i++) {
        $response = $this->getJson('/api/weather-monitors');
        $response->assertStatus(Response::HTTP_OK);
    }

    $response = $this->getJson('/api/weather-monitors');
    $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
});

test('weather-readings endpoint rate limiting works', function () {
    for ($i = 0; $i < 50; $i++) {
        $response = $this->getJson('/api/weather-readings');
        $response->assertStatus(Response::HTTP_OK);
    }

    $response = $this->getJson('/api/weather-readings');
    $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
});
