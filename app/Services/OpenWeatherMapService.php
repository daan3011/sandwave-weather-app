<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;

class OpenWeatherMapService implements OpenWeatherMapServiceInterface
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('weather_apis.providers.openweathermap.api_key');
        $this->apiUrl = config('weather_apis.providers.openweathermap.api_url');
    }

    public function getCurrentWeather(string $city): array
    {
        $response = Http::get("{$this->apiUrl}/weather", [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
        ]);

        return $response->json();
    }

    public function getFiveDayForecast(string $city): array
    {
        $response = Http::get("{$this->apiUrl}/forecast", [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
        ]);

        return $response->json();
    }

    public function getAirConditions(float $lat, float $lon): array
    {
        $response = Http::get("{$this->apiUrl}/air_pollution", [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
        ]);

        return $response->json();
    }

    public function getCoordinates(string $city): array
    {
        $response = Http::get("http://api.openweathermap.org/geo/1.0/direct", [
            'q' => $city,
            'limit' => 1,
            'appid' => $this->apiKey,
        ]);

        return $response->json()[0] ?? [];
    }

    public function extractWeatherData(array $weatherData): array
    {
        return [
            'temperature'         => $weatherData['main']['temp'],
            'feels_like'          => $weatherData['main']['feels_like'],
            'weather_description' => $weatherData['weather'][0]['description'],
            'wind_speed'          => $weatherData['wind']['speed'],
            'wind_direction'      => $weatherData['wind']['deg'],
            'chance_of_rain'      => $this->calculateChanceOfRain($weatherData),
            'weather_code'        => $weatherData['weather'][0]['id'],
            'recorded_at'         => now(),
        ];
    }

    protected function calculateChanceOfRain(array $weatherData): ?float
    {
        return $weatherData['pop'] ?? null;
    }

    public function getCombinedWeatherData(string $city): array
    {
        $coordinates = $this->getCoordinates($city);

        $lat = $coordinates['lat'];
        $lon = $coordinates['lon'];

        $currentWeather = $this->getCurrentWeather($city);
        $forecast = $this->getFiveDayForecast($city);
        $airConditions = $this->getAirConditions($lat, $lon);

        return [
            'city'             => $currentWeather['name'] ?? $city,
            'current_weather'  => $currentWeather,
            'forecast'         => $forecast,
            'air_conditions'   => $airConditions,
        ];
    }

}
