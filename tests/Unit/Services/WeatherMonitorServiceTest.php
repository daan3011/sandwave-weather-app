<?php

use App\Services\WeatherMonitorService;
use App\Interfaces\Repositories\WeatherMonitorRepositoryInterface;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use App\Models\WeatherMonitor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);
uses(TestCase::class);

beforeEach(function () {
    $this->weatherMonitorRepository = Mockery::mock(WeatherMonitorRepositoryInterface::class);
    $this->openWeatherMapService = Mockery::mock(OpenWeatherMapServiceInterface::class);

    $this->weatherMonitorService = new WeatherMonitorService(
        $this->weatherMonitorRepository,
        $this->openWeatherMapService
    );
});

afterEach(function () {
    Mockery::close();
});

test('createWeatherMonitor creates a weather monitor with populated location data', function () {
    $data = [
        'city' => 'Amsterdam',
    ];

    $locationData = [
        'lat'     => 52.3676,
        'lon'     => 4.9041,
        'country' => 'NL',
    ];

    $expectedData = array_merge($data, [
        'latitude'  => $locationData['lat'],
        'longitude' => $locationData['lon'],
        'country'   => $locationData['country'],
    ]);

    $weatherMonitor = new WeatherMonitor($expectedData);

    $this->openWeatherMapService
        ->shouldReceive('getCoordinates')
        ->with($data['city'])
        ->once()
        ->andReturn($locationData);

    $this->weatherMonitorRepository
        ->shouldReceive('create')
        ->with($expectedData)
        ->once()
        ->andReturn($weatherMonitor);

    $result = $this->weatherMonitorService->createWeatherMonitor($data);

    expect($result)->toBeInstanceOf(WeatherMonitor::class);
    expect($result->city)->toBe($data['city']);
    expect($result->latitude)->toBe($locationData['lat']);
    expect($result->longitude)->toBe($locationData['lon']);
    expect($result->country)->toBe($locationData['country']);
});

test('getWeatherMonitor returns a weather monitor when found', function () {
    $id = 1;
    $weatherMonitor = WeatherMonitor::factory()->make(['id' => $id]);

    $this->weatherMonitorRepository
        ->shouldReceive('find')
        ->with($id)
        ->once()
        ->andReturn($weatherMonitor);

    $result = $this->weatherMonitorService->getWeatherMonitor($id);

    expect($result)->toBeInstanceOf(WeatherMonitor::class);
    expect($result->id)->toBe($id);
});

test('getWeatherMonitor returns null when not found', function () {
    $id = 1;

    $this->weatherMonitorRepository
        ->shouldReceive('find')
        ->with($id)
        ->once()
        ->andReturn(null);

    $result = $this->weatherMonitorService->getWeatherMonitor($id);

    expect($result)->toBeNull();
});

test('deleteWeatherMonitor deletes a weather monitor successfully', function () {
    $weatherMonitor = WeatherMonitor::factory()->make();

    $this->weatherMonitorRepository
        ->shouldReceive('delete')
        ->with($weatherMonitor)
        ->once()
        ->andReturn(true);

    $result = $this->weatherMonitorService->deleteWeatherMonitor($weatherMonitor);

    expect($result)->toBeTrue();
});

test('deleteWeatherMonitor returns false when deletion fails', function () {
    $weatherMonitor = WeatherMonitor::factory()->make();

    $this->weatherMonitorRepository
        ->shouldReceive('delete')
        ->with($weatherMonitor)
        ->once()
        ->andReturn(false);

    $result = $this->weatherMonitorService->deleteWeatherMonitor($weatherMonitor);

    expect($result)->toBeFalse();
});

test('listWeatherMonitors returns paginated weather monitors', function () {
    $perPage = 15;
    $weatherMonitors = WeatherMonitor::factory()->count(3)->make();

    $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
        $weatherMonitors,
        $weatherMonitors->count(),
        $perPage,
        1,
        ['path' => url('/')]
    );

    $this->weatherMonitorRepository
        ->shouldReceive('getAll')
        ->with($perPage)
        ->once()
        ->andReturn($paginator);

    $result = $this->weatherMonitorService->listWeatherMonitors($perPage);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);
    expect($result->items())->toEqual($weatherMonitors->all());
    expect($result->perPage())->toBe($perPage);
});
