<?php

use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Exceptions\FetchWeatherDataException;
use App\Services\CachingOpenWeatherMapService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

uses(RefreshDatabase::class);
uses(TestCase::class);

beforeEach(function () {
    $this->originalService = Mockery::mock(OpenWeatherMapServiceInterface::class);
    $this->cache = Mockery::mock(CacheRepository::class);
    config(['weather.cache_ttl_minutes' => 5]); // Adjust as needed
    $this->decorator = new CachingOpenWeatherMapService($this->originalService, $this->cache);
});

afterEach(function () {
    Mockery::close();
});

it('returns cached data if available without calling the original service', function () {
    $city = 'Amsterdam';
    $normalizedCity = 'amsterdam';
    $cacheKey = "weather_data_{$normalizedCity}";
    $cachedData = [
        'city' => 'Amsterdam',
        'current_weather' => ['temp' => 20],
        'forecast' => [],
        'air_conditions' => [],
    ];

    // Use Mockery::type to match any Carbon instance and any Closure
    $this->cache
        ->shouldReceive('remember')
        ->once()
        ->with($cacheKey, Mockery::type(Carbon::class), Mockery::type(\Closure::class))
        ->andReturn($cachedData);

    // The original service should not be called
    $this->originalService
        ->shouldNotReceive('getCombinedWeatherData');

    $result = $this->decorator->getCombinedWeatherData($city);

    expect($result)->toEqual($cachedData);
});

it('delegates getCurrentWeather to the original service', function () {
    $city = 'Amsterdam';
    $currentWeather = ['temp' => 20, 'description' => 'clear sky'];

    $this->originalService
        ->shouldReceive('getCurrentWeather')
        ->once()
        ->with($city)
        ->andReturn($currentWeather);

    $result = $this->decorator->getCurrentWeather($city);

    expect($result)->toEqual($currentWeather);
});

it('delegates getFiveDayForecast to the original service', function () {
    $city = 'Amsterdam';
    $forecast = ['day1' => [], 'day2' => []];

    $this->originalService
        ->shouldReceive('getFiveDayForecast')
        ->once()
        ->with($city)
        ->andReturn($forecast);

    $result = $this->decorator->getFiveDayForecast($city);

    expect($result)->toEqual($forecast);
});

it('delegates getAirConditions to the original service', function () {
    $lat = 52.3676;
    $lon = 4.9041;
    $airConditions = ['aqi' => 1];

    $this->originalService
        ->shouldReceive('getAirConditions')
        ->once()
        ->with($lat, $lon)
        ->andReturn($airConditions);

    $result = $this->decorator->getAirConditions($lat, $lon);

    expect($result)->toEqual($airConditions);
});

it('delegates getCoordinates to the original service', function () {
    $city = 'Amsterdam';
    $coordinates = ['lat' => 52.3676, 'lon' => 4.9041];

    $this->originalService
        ->shouldReceive('getCoordinates')
        ->once()
        ->with($city)
        ->andReturn($coordinates);

    $result = $this->decorator->getCoordinates($city);

    expect($result)->toEqual($coordinates);
});

it('delegates extractWeatherData to the original service', function () {
    $weatherData = [
        'main' => ['temp' => 20, 'feels_like' => 19],
        'weather' => [['description' => 'clear sky', 'id' => 800]],
        'wind' => ['speed' => 5, 'deg' => 180],
        'pop' => 0.0,
    ];
    $extractedData = [
        'temperature' => 20,
        'feels_like' => 19,
        'weather_description' => 'clear sky',
        'wind_speed' => 5,
        'wind_direction' => 180,
        'chance_of_rain' => 0.0,
        'weather_code' => 800,
        'recorded_at' => now(),
    ];

    $this->originalService
        ->shouldReceive('extractWeatherData')
        ->once()
        ->with($weatherData)
        ->andReturn($extractedData);

    $result = $this->decorator->extractWeatherData($weatherData);

    expect($result)->toEqual($extractedData);
});
