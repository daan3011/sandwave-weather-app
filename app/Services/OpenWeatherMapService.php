<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenWeatherMapService
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('weather-apis.providers.openweathermap.api_key');
        $this->apiUrl = config('weather-apis.providers.openweathermap.api_url');
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
}
