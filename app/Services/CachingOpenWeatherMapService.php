<?php

namespace App\Services;

use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Str;
use App\Exceptions\FetchWeatherDataException;

class CachingOpenWeatherMapService implements OpenWeatherMapServiceInterface
{
    protected OpenWeatherMapServiceInterface $service;
    protected CacheRepository $cache;

    /**
     * @param OpenWeatherMapServiceInterface $service The service to decorate.
     * @param CacheRepository.
     */
    public function __construct(OpenWeatherMapServiceInterface $service, CacheRepository $cache)
    {
        $this->service = $service;
        $this->cache = $cache;
    }

    /**
     * Get combined weather data for a given city, with caching.
     *
     * @param string $city
     * @return array
     * @throws FetchWeatherDataException
     */
    public function getCombinedWeatherData(string $city): array
    {
        // Normalize the city name for cache key
        $normalizedCity = Str::slug(strtolower(trim($city)), '_');
        $cacheKey = "weather_data_{$normalizedCity}";
        $cacheTTL = now()->addMinutes(config('weather.weather_overview_cache_ttl'));

        return $this->cache->remember($cacheKey, $cacheTTL, function () use ($city) {
            return $this->service->getCombinedWeatherData($city);
        });
    }

    public function getCurrentWeather(string $city): array
    {
        return $this->service->getCurrentWeather($city);
    }

    public function getFiveDayForecast(string $city): array
    {
        return $this->service->getFiveDayForecast($city);
    }

    public function getAirConditions(float $lat, float $lon): array
    {
        return $this->service->getAirConditions($lat, $lon);
    }

    public function getCoordinates(string $city): array
    {
        return $this->service->getCoordinates($city);
    }

    public function extractWeatherData(array $weatherData): array
    {
        return $this->service->extractWeatherData($weatherData);
    }
}
