<?php

namespace App\Services;

use App\Models\WeatherMonitor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Services\WeatherMonitorServiceInterface;
use App\Interfaces\Repositories\WeatherMonitorRepositoryInterface;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;

class WeatherMonitorService implements WeatherMonitorServiceInterface
{
    protected WeatherMonitorRepositoryInterface $weatherMonitorRepository;
    protected OpenWeatherMapServiceInterface $openWeatherMapService;

    public function __construct(
        WeatherMonitorRepositoryInterface $weatherMonitorRepository,
        OpenWeatherMapServiceInterface $openWeatherMapService
    ) {
        $this->weatherMonitorRepository = $weatherMonitorRepository;
        $this->openWeatherMapService = $openWeatherMapService;
    }

    public function createWeatherMonitor(array $data): WeatherMonitor
    {
        $this->populateLocationData($data);

        return $this->weatherMonitorRepository->create($data);
    }

    public function getWeatherMonitor(int $id): ?WeatherMonitor
    {
        return $this->weatherMonitorRepository->find($id);
    }

    public function deleteWeatherMonitor(WeatherMonitor $weatherMonitor): bool
    {
        return $this->weatherMonitorRepository->delete($weatherMonitor);
    }

    public function listWeatherMonitors(int $perPage = 15): LengthAwarePaginator
    {
        return $this->weatherMonitorRepository->getAll($perPage);
    }

    protected function populateLocationData(array &$data): void
    {
        $locationData = $this->openWeatherMapService->getCoordinates($data['city']);

        if ($locationData) {
            $data['latitude'] = $locationData['lat'] ?? null;
            $data['longitude'] = $locationData['lon'] ?? null;
            $data['country'] = $locationData['country'] ?? null;
        }
    }
}
