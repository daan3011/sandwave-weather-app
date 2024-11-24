<?php

namespace App\Services;

use App\Models\WeatherMonitor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Services\WeatherMonitorServiceInterface;
use App\Interfaces\Repositories\WeatherMonitorRepositoryInterface;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;

class WeatherMonitorService implements WeatherMonitorServiceInterface
{
    /**
     * @var WeatherMonitorRepositoryInterface
     */
    protected WeatherMonitorRepositoryInterface $weatherMonitorRepository;

    /**
     * The service for interacting with the OpenWeatherMap API.
     *
     * @var OpenWeatherMapServiceInterface
     */
    protected OpenWeatherMapServiceInterface $openWeatherMapService;

    /**
     * WeatherMonitorService constructor.
     *
     * @param WeatherMonitorRepositoryInterface $weatherMonitorRepository Repository for weather monitor data.
     * @param OpenWeatherMapServiceInterface $openWeatherMapService Service for OpenWeatherMap API interactions.
     */
    public function __construct(
        WeatherMonitorRepositoryInterface $weatherMonitorRepository,
        OpenWeatherMapServiceInterface $openWeatherMapService
    ) {
        $this->weatherMonitorRepository = $weatherMonitorRepository;
        $this->openWeatherMapService = $openWeatherMapService;
    }

    /**
     * Creates a new weather monitor record.
     *
     * @param array $data The data for the new weather monitor, including location details.
     *
     * @return WeatherMonitor The created weather monitor instance.
     */
    public function createWeatherMonitor(array $data): WeatherMonitor
    {
        $this->populateLocationData($data);

        return $this->weatherMonitorRepository->create($data);
    }

    /**
     * Retrieves a weather monitor by its ID.
     *
     * @param int $id The ID of the weather monitor to retrieve.
     *
     * @return WeatherMonitor|null The weather monitor instance or null if not found.
     */
    public function getWeatherMonitor(int $id): ?WeatherMonitor
    {
        return $this->weatherMonitorRepository->find($id);
    }

    /**
     * Deletes a weather monitor.
     *
     * @param WeatherMonitor $weatherMonitor The weather monitor instance to delete.
     *
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteWeatherMonitor(WeatherMonitor $weatherMonitor): bool
    {
        return $this->weatherMonitorRepository->delete($weatherMonitor);
    }

    /**
     * Retrieves a paginated list of weather monitors.
     *
     * @param int $perPage The number of records to display per page (default: 15).
     *
     * @return LengthAwarePaginator A paginator instance containing the weather monitors.
     */
    public function listWeatherMonitors(int $perPage = 15): LengthAwarePaginator
    {
        return $this->weatherMonitorRepository->getAll($perPage);
    }

    /**
     * Populates location data (latitude, longitude, and country) into the given data array
     * using the OpenWeatherMap API.
     *
     * @param array $data The reference to the data array to update with location details.
     *
     * @return void
     */
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
