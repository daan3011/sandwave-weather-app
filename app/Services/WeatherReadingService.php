<?php

namespace App\Services;

use App\Models\WeatherReading;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Services\WeatherReadingServiceInterface;
use App\Interfaces\Repositories\WeatherReadingRepositoryInterface;

class WeatherReadingService implements WeatherReadingServiceInterface
{
    /**
     * @var WeatherReadingRepositoryInterface
     */
    protected WeatherReadingRepositoryInterface $weatherReadingRepository;

    /**
     * @param WeatherReadingRepositoryInterface $weatherReadingRepository The repository for weather readings.
     */
    public function __construct(
        WeatherReadingRepositoryInterface $weatherReadingRepository
    ) {
        $this->weatherReadingRepository = $weatherReadingRepository;
    }

    /**
     * Creates a new weather reading record.
     *
     * @param array $data The data for the new weather reading.
     *
     * @return WeatherReading The created weather reading instance.
     */
    public function createWeatherReading(array $data): WeatherReading
    {
        return $this->weatherReadingRepository->create($data);
    }

    /**
     * Retrieves a paginated list of weather readings, optionally filtered by the provided criteria.
     *
     * @param array $filters The filters to apply to the weather readings (e.g., date range, location).
     * @param int $perPage The number of records to display per page.
     *
     * @return LengthAwarePaginator A paginator instance containing the filtered weather readings.
     */
    public function listWeatherReadings(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->weatherReadingRepository->getAll($filters, $perPage);
    }
}
