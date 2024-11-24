<?php

use App\Models\WeatherMonitor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Interfaces\Services\WeatherMonitorServiceInterface;
use Illuminate\Http\Response;

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

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(3, 'data');
});

test('index returns empty list when no weather monitors exist', function () {
    $currentPage = 1;
    $perPage = 15;
    $emptyPaginator = new LengthAwarePaginator(
        collect([]),
        0,
        $perPage,
        $currentPage
    );

    $this->weatherMonitorService
        ->shouldReceive('listWeatherMonitors')
        ->with($perPage)
        ->once()
        ->andReturn($emptyPaginator);

    $response = $this->getJson('/api/weather-monitors');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(0, 'data');
});

test('store creates a new weather monitor successfully', function () {
    $data = [
        'city' => 'Test city',
        'interval_minutes' => 30,
    ];

    $weatherMonitor = WeatherMonitor::factory()->make($data);

    $this->weatherMonitorService
        ->shouldReceive('createWeatherMonitor')
        ->with($data)
        ->once()
        ->andReturn($weatherMonitor);

    $response = $this->postJson('/api/weather-monitors', $data);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonFragment(['city' => 'Test city']);
});

test('store returns validation errors when required fields are missing', function () {
    $data = [];

    $response = $this->postJson('/api/weather-monitors', $data);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['city', 'interval_minutes']);
});

test('show returns a weather monitor when it exists', function () {
    $weatherMonitor = WeatherMonitor::factory()->create();

    $response = $this->getJson("/api/weather-monitors/{$weatherMonitor->id}");

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonFragment(['id' => $weatherMonitor->id]);
});

test('show returns 404 when weather monitor does not exist', function () {
    $nonExistentId = 9999;

    $response = $this->getJson("/api/weather-monitors/{$nonExistentId}");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('destroy deletes a weather monitor when it exists', function () {
    $weatherMonitor = WeatherMonitor::factory()->create();

    $this->weatherMonitorService
        ->shouldReceive('deleteWeatherMonitor')
        ->withArgs(function ($arg) use ($weatherMonitor) {
            return $arg->id === $weatherMonitor->id;
        })
        ->once()
        ->andReturn(true);

    $response = $this->deleteJson("/api/weather-monitors/{$weatherMonitor->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
});

test('destroy returns 404 when weather monitor does not exist', function () {
    $nonExistentId = 9999;

    $response = $this->deleteJson("/api/weather-monitors/{$nonExistentId}");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});
