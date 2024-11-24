<?php

namespace App\Services;

use App\Models\WeatherReading;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Services\WeatherReadingServiceInterface;
use App\Interfaces\Repositories\WeatherReadingRepositoryInterface;

class WeatherReadingService implements WeatherReadingServiceInterface
{
    protected WeatherReadingRepositoryInterface $weatherReadingRepository;

    public function __construct(
        WeatherReadingRepositoryInterface $weatherReadingRepository,
    ) {
        $this->weatherReadingRepository = $weatherReadingRepository;
    }

    public function createWeatherReading(array $data): WeatherReading
    {
        return $this->weatherReadingRepository->create($data);
    }

    public function listWeatherReadings(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->weatherReadingRepository->getAll($filters, $perPage);
    }
}
