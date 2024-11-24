<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\GetWeatherRequest;
use App\Http\Resources\WeatherDataResource;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;

class WeatherController extends Controller
{
    protected OpenWeatherMapServiceInterface $weatherService;

    public function __construct(OpenWeatherMapServiceInterface $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    /**
     * Handle the incoming request.
     */
    public function index(GetWeatherRequest $request): JsonResponse
    {
        $city = $request->validated()['city'];

        try {
            $weatherData = $this->weatherService->getCombinedWeatherData($city);

            return response()->json(new WeatherDataResource($weatherData), 200);
        } catch (\Exception $e) {
            Log::error('Weather data fetch error: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch weather data for city.'], 422);
        }
    }
}
