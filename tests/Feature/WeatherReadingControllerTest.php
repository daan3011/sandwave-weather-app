<?php

use App\Models\WeatherMonitor;
use App\Models\WeatherReading;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Interfaces\Services\WeatherReadingServiceInterface;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->weatherReadingService = Mockery::mock(WeatherReadingServiceInterface::class);
    $this->app->instance(WeatherReadingServiceInterface::class, $this->weatherReadingService);
});

afterEach(function () {
    Mockery::close();
});

test('index returns weather readings with filters', function () {
    $weatherMonitor = WeatherMonitor::factory()->create();

    $filters = [
        'weather_monitor_id' => $weatherMonitor->id,
        'start_date'         => '2023-01-01 00:00:00',
        'end_date'           => '2023-01-31 23:59:59',
    ];

    $weatherReadings = WeatherReading::factory()->count(3)->make([
        'monitor_id'  => $weatherMonitor->id,
        'recorded_at' => now(),
    ]);

    $currentPage = 1;
    $perPage = 15;
    $paginator = new LengthAwarePaginator(
        $weatherReadings,
        $weatherReadings->count(),
        $perPage,
        $currentPage
    );

    $this->weatherReadingService
        ->shouldReceive('listWeatherReadings')
        ->with($filters, 15)
        ->once()
        ->andReturn($paginator);

    $response = $this->getJson('/api/weather-readings?' . http_build_query($filters));

    $response->assertStatus(200)
             ->assertJsonCount(3, 'data');
});
