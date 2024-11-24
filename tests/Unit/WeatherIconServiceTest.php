<?php

use Tests\TestCase;
use App\Interfaces\Services\WeatherIconServiceInterface;

uses(TestCase::class);

it('returns correct icon for weather codes', function () {
    $weatherIconService = app(WeatherIconServiceInterface::class);

    expect($weatherIconService->getIcon(202))->toBe('⛈️');
    expect($weatherIconService->getIcon(310))->toBe('🌦️');
    expect($weatherIconService->getIcon(521))->toBe('🌧️');
    expect($weatherIconService->getIcon(611))->toBe('❄️');
    expect($weatherIconService->getIcon(800))->toBe('☀️');
    expect($weatherIconService->getIcon(803))->toBe('☁️');
    expect($weatherIconService->getIcon(999))->toBe('☁️'); // unknown code
});
