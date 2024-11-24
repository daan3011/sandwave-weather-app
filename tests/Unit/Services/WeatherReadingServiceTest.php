<?php

use App\Services\WeatherReadingService;
use App\Interfaces\Repositories\WeatherReadingRepositoryInterface;
use App\Models\WeatherReading;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);
uses(TestCase::class);

beforeEach(function () {
    $this->weatherReadingRepository = Mockery::mock(WeatherReadingRepositoryInterface::class);

    $this->weatherReadingService = new WeatherReadingService(
        $this->weatherReadingRepository
    );
});

afterEach(function () {
    Mockery::close();
});

test('createWeatherReading creates a weather reading successfully', function () {
    $data = [
        'weather_monitor_id'          => 1,
        'temperature'         => 25.5,
        'feels_like'          => 27.0,
        'weather_description' => 'Sunny',
        'wind_speed'          => 5.5,
        'wind_direction'      => 180,
        'chance_of_rain'      => 10,
        'recorded_at'         => now(),
    ];

    $weatherReading = new WeatherReading($data);

    $this->weatherReadingRepository
        ->shouldReceive('create')
        ->with($data)
        ->once()
        ->andReturn($weatherReading);

    $result = $this->weatherReadingService->createWeatherReading($data);

    expect($result)->toBeInstanceOf(WeatherReading::class);
    expect($result->weather_monitor_id)->toBe($data['weather_monitor_id']);
    expect($result->temperature)->toBe($data['temperature']);
    expect($result->weather_description)->toBe($data['weather_description']);
});

test('createWeatherReading throws an exception when required data is missing', function () {
    $data = [
        'temperature' => 25.5,
    ];

    $this->weatherReadingRepository
        ->shouldReceive('create')
        ->with($data)
        ->once()
        ->andThrow(new \Exception('Monitor ID is required'));

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Monitor ID is required');

    $this->weatherReadingService->createWeatherReading($data);
});

test('listWeatherReadings returns paginated weather readings with filters', function () {
    $filters = [
        'monitor_id' => 1,
        'start_date' => '2023-01-01',
        'end_date'   => '2023-01-31',
    ];

    $perPage = 15;
    $weatherReadings = WeatherReading::factory()->count(3)->make();

    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $weatherReadings,
        $weatherReadings->count(),
        $perPage,
        1,
        ['path' => url('/')]
    );

    $this->weatherReadingRepository
        ->shouldReceive('getAll')
        ->with($filters, $perPage)
        ->once()
        ->andReturn($paginator);

    $result = $this->weatherReadingService->listWeatherReadings($filters, $perPage);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($result->items())->toEqual($weatherReadings->all());
    expect($result->perPage())->toBe($perPage);
});

test('listWeatherReadings returns paginated weather readings without filters', function () {
    $filters = []; // No filters
    $perPage = 15;
    $weatherReadings = WeatherReading::factory()->count(5)->make();

    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $weatherReadings,
        $weatherReadings->count(),
        $perPage,
        1,
        ['path' => url('/')]
    );

    $this->weatherReadingRepository
        ->shouldReceive('getAll')
        ->with($filters, $perPage)
        ->once()
        ->andReturn($paginator);

    $result = $this->weatherReadingService->listWeatherReadings($filters, $perPage);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($result->items())->toEqual($weatherReadings->all());
    expect($result->perPage())->toBe($perPage);
});
