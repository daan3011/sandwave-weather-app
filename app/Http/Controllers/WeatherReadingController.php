<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\WeatherReadingResource;
use App\Http\Requests\ListWeatherReadingsRequest;
use App\Interfaces\Services\WeatherReadingServiceInterface;

class WeatherReadingController extends Controller
{
    const PER_PAGE = 15;

    protected WeatherReadingServiceInterface $weatherReadingService;

    public function __construct(WeatherReadingServiceInterface $weatherReadingService)
    {
        $this->weatherReadingService = $weatherReadingService;
    }

    public function index(ListWeatherReadingsRequest $request): JsonResponse
    {
        $filters = $request->validated();

        $perPage = $filters['per_page'] ?? self::PER_PAGE;

        $weatherReadings = $this->weatherReadingService->listWeatherReadings($filters, $perPage);

        return WeatherReadingResource::collection($weatherReadings)->response();
    }
}
