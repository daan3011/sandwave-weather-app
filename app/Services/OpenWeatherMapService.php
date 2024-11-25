<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Exceptions\MissingApiKeyException;
use Illuminate\Http\Client\RequestException;
use App\Exceptions\FetchWeatherDataException;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use Illuminate\Http\Response;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class OpenWeatherMapService implements OpenWeatherMapServiceInterface
{
    private const CURRENT_WEATHER_ENDPOINT = '/weather';
    private const FORECAST_ENDPOINT        = '/forecast';
    private const AIR_POLLUTION_ENDPOINT   = '/air_pollution';
    private const GEO_COORDINATES_ENDPOINT  = '/direct';

    protected string $apiKey;
    protected string $apiUrl;
    protected string $geoApiUrl;
    // protected CacheRepository $cache;

    /**
     * OpenWeatherMapService constructor.
     *
     * @throws MissingApiKeyException
     */
    public function __construct()
    {
        $this->apiKey = config('weather.providers.openweathermap.api_key');
        $this->apiUrl = config('weather.providers.openweathermap.api_url');
        $this->geoApiUrl = config('weather.providers.openweathermap.geo_api_url');

        if (empty($this->apiKey)) {
            throw new MissingApiKeyException('The OpenWeatherMap API key is not set in the configuration.');
        }
    }

    /**
     * Make an HTTP GET request to the given URL with the provided parameters.
     *
     * @param string $url
     * @param array $params
     * @return array
     * @throws FetchWeatherDataException
     */
    protected function makeRequest(string $url, array $params = []): array
    {
        $params['appid'] = $this->apiKey;

        try {
            $response = Http::get($url, $params)->throw();
            return $response->json();
        } catch (RequestException $e) {
            $status = $e->response->status();
            $errorBody = $e->response->body();

            Log::error("OpenWeatherMap API error ({$status}): {$errorBody}");

            $message = "Failed to fetch data from OpenWeatherMap API. Status code: {$status}";

            $errorData = $e->response->json();
            if (isset($errorData['message'])) {
                $message .= ' - ' . $errorData['message'];
            }

            throw new FetchWeatherDataException($message, $status, $e);
        }
    }

    /**
     * Get current weather data for a given city.
     *
     * @param string $city
     * @return array
     * @throws FetchWeatherDataException
     */
    public function getCurrentWeather(string $city): array
    {
        $url = $this->apiUrl . self::CURRENT_WEATHER_ENDPOINT;
        $params = [
            'q'     => $city,
            'units' => 'metric',
        ];

        return $this->makeRequest($url, $params);
    }

    /**
     * Get five-day forecast data for a given city.
     *
     * @param string $city
     * @return array
     * @throws FetchWeatherDataException
     */
    public function getFiveDayForecast(string $city): array
    {
        $url = $this->apiUrl . self::FORECAST_ENDPOINT;
        $params = [
            'q'     => $city,
            'units' => 'metric',
        ];

        return $this->makeRequest($url, $params);
    }

    /**
     * Get air pollution data for given latitude and longitude.
     *
     * @param float $lat
     * @param float $lon
     * @return array
     * @throws FetchWeatherDataException
     */
    public function getAirConditions(float $lat, float $lon): array
    {
        $url = $this->apiUrl . self::AIR_POLLUTION_ENDPOINT;
        $params = [
            'lat' => $lat,
            'lon' => $lon,
        ];

        return $this->makeRequest($url, $params);
    }

    /**
     * Get coordinates (latitude and longitude) for a given city.
     *
     * @param string $city
     * @return array
     * @throws FetchWeatherDataException
     */
    public function getCoordinates(string $city): array
    {
        $url = $this->geoApiUrl . self::GEO_COORDINATES_ENDPOINT;
        $params = [
            'q'     => $city,
            'limit' => 1,
        ];

        $response = $this->makeRequest($url, $params);

        if (empty($response)) {
            Log::error("No coordinates found for city: {$city}");
            throw new FetchWeatherDataException("No coordinates found for city: {$city}", Response::HTTP_NOT_FOUND);
        }

        return $response[0] ?? [];
    }

    /**
     * Extract weather data from the API response.
     *
     * @param array $weatherData
     * @return array
     */
    public function extractWeatherData(array $weatherData): array
    {
        return [
            'temperature'         => $weatherData['main']['temp'] ?? null,
            'feels_like'          => $weatherData['main']['feels_like'] ?? null,
            'weather_description' => $weatherData['weather'][0]['description'] ?? null,
            'wind_speed'          => $weatherData['wind']['speed'] ?? null,
            'wind_direction'      => $weatherData['wind']['deg'] ?? null,
            'chance_of_rain'      => $this->calculateChanceOfRain($weatherData),
            'weather_code'        => $weatherData['weather'][0]['id'] ?? null,
            'recorded_at'         => now(),
        ];
    }

    /**
     * Calculate the chance of rain from the weather data.
     *
     * @param array $weatherData
     * @return float|null
     */
    protected function calculateChanceOfRain(array $weatherData): ?float
    {
        return $weatherData['pop'] ?? null;
    }

    /**
     * Get combined weather data for a given city, including current weather, forecast, and air conditions.
     *
     * @param string $city
     * @return array
     * @throws FetchWeatherDataException
     */
    public function getCombinedWeatherData(string $city): array
    {
        $coordinates = $this->getCoordinates($city);

        $lat = $coordinates['lat'];
        $lon = $coordinates['lon'];

        $currentWeather = $this->getCurrentWeather($city);
        $forecast       = $this->getFiveDayForecast($city);
        $airConditions  = $this->getAirConditions($lat, $lon);

        return [
            'city'            => $currentWeather['name'] ?? $city,
            'current_weather' => $currentWeather,
            'forecast'        => $forecast,
            'air_conditions'  => $airConditions,
        ];
    }
}
