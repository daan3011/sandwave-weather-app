<?php
namespace App\Interfaces\Services;

interface OpenWeatherMapServiceInterface
{
    public function getCurrentWeather(string $city): array;
    public function getFiveDayForecast(string $city): array;
    public function getAirConditions(float $lat, float $lon): array;
    public function getCoordinates(string $city): array;
    public function extractWeatherData(array $weatherData): array;
}
