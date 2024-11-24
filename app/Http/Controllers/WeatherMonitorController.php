<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Models\WeatherMonitor;
use App\Http\Resources\WeatherMonitorResource;
use App\Http\Requests\StoreWeatherMonitorRequest;
use App\Interfaces\Services\WeatherMonitorServiceInterface;
use App\Exceptions\WeatherMonitorNotFoundException;

class WeatherMonitorController extends Controller
{
    protected const PER_PAGE = 15;

    protected WeatherMonitorServiceInterface $weatherMonitorService;

    public function __construct(WeatherMonitorServiceInterface $weatherMonitorService)
    {
        $this->weatherMonitorService = $weatherMonitorService;
    }

    /**
     * Display a listing of the weather monitors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', self::PER_PAGE);

        $monitors = $this->weatherMonitorService->listWeatherMonitors($perPage);

        return WeatherMonitorResource::collection($monitors)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created weather monitor in storage.
     *
     * @param  \App\Http\Requests\StoreWeatherMonitorRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreWeatherMonitorRequest $request): JsonResponse
    {
        $weatherMonitor = $this->weatherMonitorService->createWeatherMonitor($request->validated());

        return (new WeatherMonitorResource($weatherMonitor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified weather monitor.
     *
     * @param  \App\Models\WeatherMonitor  $weatherMonitor
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(WeatherMonitor $weatherMonitor): JsonResponse
    {
        return (new WeatherMonitorResource($weatherMonitor))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified weather monitor from storage.
     *
     * @param  \App\Models\WeatherMonitor  $weatherMonitor
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(WeatherMonitor $weatherMonitor): JsonResponse
    {
        $this->weatherMonitorService->deleteWeatherMonitor($weatherMonitor);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
