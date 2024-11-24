<?php

use App\Services\OpenWeatherMapService;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);
uses(TestCase::class);

beforeEach(function () {
    $this->openWeatherMapService = new OpenWeatherMapService();
});

afterEach(function () {
    Mockery::close();
});

test('getCurrentWeather returns weather data for a valid city', function () {
    $city = 'Amsterdam';
    $mockResponse = [
        'weather' => [
            [
                'id'          => 800,
                'main'        => 'Clear',
                'description' => 'clear sky',
                'icon'        => '01d',
            ],
        ],
        'main' => [
            'temp'      => 20.0,
            'feels_like'=> 19.5,
        ],
        'name' => 'Amsterdam',
    ];

    Http::fake([
        'api.openweathermap.org/data/2.5/weather*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->openWeatherMapService->getCurrentWeather($city);

    expect($result)->toBeArray();
    expect($result['name'])->toBe('Amsterdam');
    expect($result['main']['temp'])->toEqualWithDelta(20.0, 0.1);
    expect($result['weather'][0]['description'])->toBe('clear sky');
});


test('getFiveDayForecast returns forecast data for a valid city', function () {
    $city = 'Amsterdam';
    $mockResponse = [
        'list' => [
            [
                'dt_txt' => '2023-01-01 00:00:00',
                'main'   => ['temp' => 15.0],
                'weather'=> [
                    [
                        'id'          => 500,
                        'main'        => 'Rain',
                        'description' => 'light rain',
                        'icon'        => '10d',
                    ],
                ],
            ],
        ],
        'city' => ['name' => 'Amsterdam'],
    ];

    Http::fake([
        'api.openweathermap.org/data/2.5/forecast*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->openWeatherMapService->getFiveDayForecast($city);

    expect($result)->toBeArray();
    expect($result['city']['name'])->toBe('Amsterdam');
    expect($result['list'])->toBeArray();
    expect($result['list'][0]['main']['temp'])->toEqualWithDelta(15.0, 0.1);
});


test('getAirConditions returns air pollution data for valid coordinates', function () {
    $lat = 52.3676;
    $lon = 4.9041;
    $mockResponse = [
        'list' => [
            [
                'main' => ['aqi' => 1],
                'components' => [
                    'co'   => 201.94,
                    'no2'  => 14.4,
                    'o3'   => 76.92,
                    'pm2_5'=> 10.0,
                ],
            ],
        ],
    ];

    Http::fake([
        'api.openweathermap.org/data/2.5/air_pollution*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->openWeatherMapService->getAirConditions($lat, $lon);

    expect($result)->toBeArray();
    expect($result['list'][0]['main']['aqi'])->toBe(1);
    expect($result['list'][0]['components']['co'])->toBe(201.94);
});


test('getCoordinates returns coordinates for a valid city', function () {
    $city = 'Amsterdam';
    $mockResponse = [
        [
            'name'    => 'Amsterdam',
            'lat'     => 52.3676,
            'lon'     => 4.9041,
            'country' => 'NL',
        ],
    ];

    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->openWeatherMapService->getCoordinates($city);

    expect($result)->toBeArray();
    expect($result['name'])->toBe('Amsterdam');
    expect($result['lat'])->toBe(52.3676);
    expect($result['lon'])->toBe(4.9041);
});

test('getCoordinates returns empty array when city is not found', function () {
    $city = 'Unknown City';
    $mockResponse = [];

    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->openWeatherMapService->getCoordinates($city);

    expect($result)->toBeArray();
    expect($result)->toBeEmpty();
});

test('extractWeatherData extracts and formats weather data correctly', function () {
    $weatherData = [
        'main' => [
            'temp'      => 20.0,
            'feels_like'=> 19.5,
        ],
        'weather' => [
            [
                'id'          => 800,
                'description' => 'clear sky',
            ],
        ],
        'wind' => [
            'speed' => 5.0,
            'deg'   => 180,
        ],
        'pop' => 0.2,
    ];

    $expectedData = [
        'temperature'         => 20.0,
        'feels_like'          => 19.5,
        'weather_description' => 'clear sky',
        'wind_speed'          => 5.0,
        'wind_direction'      => 180,
        'chance_of_rain'      => 0.2,
        'weather_code'        => 800,
        'recorded_at'         => now(),
    ];

    $result = $this->openWeatherMapService->extractWeatherData($weatherData);

    expect($result['recorded_at']->format('Y-m-d H:i:s'))->toBe(now()->format('Y-m-d H:i:s'));
    unset($result['recorded_at']);
    unset($expectedData['recorded_at']);

    expect($result)->toMatchArray($expectedData);
});

test('getCombinedWeatherData returns combined weather data for a valid city', function () {
    $city = 'Amsterdam';

    $coordinatesResponse = [
        'name'    => 'Amsterdam',
        'lat'     => 52.3676,
        'lon'     => 4.9041,
        'country' => 'NL',
    ];

    $currentWeatherResponse = [
        'name' => 'Amsterdam',
        'main' => ['temp' => 20.0],
    ];

    $forecastResponse = [
        'list' => [
            [
                'dt_txt' => '2023-01-01 00:00:00',
                'main'   => ['temp' => 15.0],
            ],
        ],
        'city' => ['name' => 'Amsterdam'],
    ];

    $airConditionsResponse = [
        'list' => [
            [
                'main' => ['aqi' => 1],
            ],
        ],
    ];

    Http::fake([
        'api.openweathermap.org/geo/1.0/direct*'     => Http::response([$coordinatesResponse], 200),
        'api.openweathermap.org/data/2.5/weather*'   => Http::response($currentWeatherResponse, 200),
        'api.openweathermap.org/data/2.5/forecast*'  => Http::response($forecastResponse, 200),
        'api.openweathermap.org/data/2.5/air_pollution*' => Http::response($airConditionsResponse, 200),
    ]);

    $result = $this->openWeatherMapService->getCombinedWeatherData($city);

    expect($result)->toBeArray();
    expect($result['city'])->toBe('Amsterdam');
    expect($result['current_weather']['main']['temp'])->toEqualWithDelta(20.0, 0.1);
    expect($result['forecast'])->toMatchArray($forecastResponse);
    expect($result['air_conditions'])->toMatchArray($airConditionsResponse);
});

test('getCurrentWeather handles API errors gracefully', function () {
    $city = 'InvalidCity';

    Http::fake([
        'api.openweathermap.org/data/2.5/weather*' => Http::response([], 404),
    ]);

    $result = $this->openWeatherMapService->getCurrentWeather($city);

    expect($result)->toBeArray();
    expect($result)->toBeEmpty();
});
