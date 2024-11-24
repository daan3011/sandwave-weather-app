<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\GetWeatherRequest;
use App\Http\Resources\WeatherDataResource;
use Illuminate\Http\Response;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use App\Exceptions\WeatherDataFetchException;

class WeatherController extends Controller
{
    protected OpenWeatherMapServiceInterface $weatherService;

    public function __construct(OpenWeatherMapServiceInterface $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Display the weather data for a specified city.
     *
     * @param  \App\Http\Requests\GetWeatherRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetWeatherRequest $request): JsonResponse
    {
        $city = $request->validated()['city'];

        try {
            $weatherData = $this->weatherService->getCombinedWeatherData($city);

            return (new WeatherDataResource($weatherData))
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Weather data fetch error for city ' . $city . ': ' . $e->getMessage());

            return response()->json([
                'error' => 'Unable to fetch weather data for the specified city.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
