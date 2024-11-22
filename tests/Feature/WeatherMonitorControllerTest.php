<?php

use App\Models\WeatherMonitor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Interfaces\Services\WeatherMonitorServiceInterface;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->weatherMonitorService = Mockery::mock(WeatherMonitorServiceInterface::class);
    $this->app->instance(WeatherMonitorServiceInterface::class, $this->weatherMonitorService);
});

afterEach(function () {
    Mockery::close();
});

test('index returns a list of weather monitors', function () {
    $weatherMonitors = WeatherMonitor::factory()->count(3)->make();

    $currentPage = 1;
    $perPage = 15;
    $paginator = new LengthAwarePaginator(
        $weatherMonitors,
        $weatherMonitors->count(),
        $perPage,
        $currentPage
    );

    $this->weatherMonitorService
        ->shouldReceive('listWeatherMonitors')
        ->with($perPage)
        ->once()
        ->andReturn($paginator);

    $response = $this->getJson('/api/weather-monitors');

    $response->assertStatus(200)
             ->assertJsonCount(3, 'data');
});

test('index returns empty list when no weather monitors exist', function () {
    $totalItems = 0;
    $currentPage = 1;
    $perPage = 15;
    $emptyPaginator = new LengthAwarePaginator(
        collect([]),
        $totalItems,
        $perPage,
        $currentPage
    );

    $this->weatherMonitorService
        ->shouldReceive('listWeatherMonitors')
        ->with($perPage)
        ->once()
        ->andReturn($emptyPaginator);

    $response = $this->getJson('/api/weather-monitors');

    $response->assertStatus(200)
             ->assertJsonCount(0, 'data');
});

test('store creates a new weather monitor successfully', function () {
    $data = [
        'city' => 'Test City',
        'interval_minutes' => 30,
    ];

    $weatherMonitor = WeatherMonitor::factory()->make($data);

    $this->weatherMonitorService
        ->shouldReceive('createWeatherMonitor')
        ->with($data)
        ->once()
        ->andReturn($weatherMonitor);

    $response = $this->postJson('/api/weather-monitors', $data);

    $response->assertStatus(201)
             ->assertJsonFragment(['city' => 'Test City']);
});

test('store returns validation errors when required fields are missing', function () {
    $data = [];

    $response = $this->postJson('/api/weather-monitors', $data);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['city', 'interval_minutes']);
});

test('show returns a weather monitor when it exists', function () {
    $id = 1;
    $weatherMonitor = WeatherMonitor::factory()->make(['id' => $id]);

    $this->weatherMonitorService
        ->shouldReceive('getWeatherMonitor')
        ->with($id)
        ->once()
        ->andReturn($weatherMonitor);

    $response = $this->getJson("/api/weather-monitors/{$id}");

    $response->assertStatus(200)
             ->assertJsonFragment(['id' => $id]);
});

test('show returns 404 when weather monitor does not exist', function () {
    $id = 999;

    $this->weatherMonitorService
        ->shouldReceive('getWeatherMonitor')
        ->with($id)
        ->once()
        ->andReturn(null);

    $response = $this->getJson("/api/weather-monitors/{$id}");

    $response->assertStatus(404)
             ->assertJsonFragment(['message' => 'Weather monitor not found.']);
});

test('destroy deletes a weather monitor when it exists', function () {
    $id = 1;
    $weatherMonitor = WeatherMonitor::factory()->make(['id' => $id]);

    $this->weatherMonitorService
        ->shouldReceive('getWeatherMonitor')
        ->with($id)
        ->once()
        ->andReturn($weatherMonitor);

    $this->weatherMonitorService
        ->shouldReceive('deleteWeatherMonitor')
        ->with($weatherMonitor)
        ->once()
        ->andReturn(true);

    $response = $this->deleteJson("/api/weather-monitors/{$id}");

    $response->assertStatus(204);
});

test('destroy returns 404 when weather monitor does not exist', function () {
    $id = 999;

    $this->weatherMonitorService
        ->shouldReceive('getWeatherMonitor')
        ->with($id)
        ->once()
        ->andReturn(null);

    $response = $this->deleteJson("/api/weather-monitors/{$id}");

    $response->assertStatus(404)
             ->assertJsonFragment(['message' => 'Weather monitor not found.']);
});

