<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\WeatherMonitorResource;
use App\Http\Requests\StoreWeatherMonitorRequest;
use App\Interfaces\Services\WeatherMonitorServiceInterface;

class WeatherMonitorController extends Controller
{
    const PER_PAGE = 15;

    protected WeatherMonitorServiceInterface $weatherMonitorService;

    public function __construct(WeatherMonitorServiceInterface $weatherMonitorService)
    {
        $this->weatherMonitorService = $weatherMonitorService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', self::PER_PAGE);
        $monitors = $this->weatherMonitorService->listWeatherMonitors($perPage);

        return WeatherMonitorResource::collection($monitors)->response();
    }

    public function store(StoreWeatherMonitorRequest $request): JsonResponse
    {
        $weatherMonitor = $this->weatherMonitorService->createWeatherMonitor($request->validated());

        return (new WeatherMonitorResource($weatherMonitor))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $weatherMonitor = $this->weatherMonitorService->getWeatherMonitor($id);

        if (!$weatherMonitor) {
            return response()->json(['message' => 'Weather monitor not found.'], 404);
        }

        return (new WeatherMonitorResource($weatherMonitor))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $weatherMonitor = $this->weatherMonitorService->getWeatherMonitor($id);

        if (!$weatherMonitor) {
            return response()->json(['message' => 'Weather monitor not found.'], 404);
        }

        $this->weatherMonitorService->deleteWeatherMonitor($weatherMonitor);

        return response()->json(null, 204);
    }
}
