<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Resources\WeatherReadingResource;
use App\Http\Requests\ListWeatherReadingsRequest;
use App\Interfaces\Services\WeatherReadingServiceInterface;

class WeatherReadingController extends Controller
{
    protected const PER_PAGE = 15;

    protected WeatherReadingServiceInterface $weatherReadingService;

    public function __construct(WeatherReadingServiceInterface $weatherReadingService)
    {
        $this->weatherReadingService = $weatherReadingService;
    }

    /**
     * Display a listing of the weather readings.
     *
     * @param  \App\Http\Requests\ListWeatherReadingsRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListWeatherReadingsRequest $request): JsonResponse
    {
        $filters = $request->validated();

        $perPage = (int) ($filters['per_page'] ?? self::PER_PAGE);

        $weatherReadings = $this->weatherReadingService->listWeatherReadings($filters, $perPage);

        return WeatherReadingResource::collection($weatherReadings)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
