<?php

namespace App\Interfaces\Services;

use App\Models\WeatherReading;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WeatherReadingServiceInterface
{
    public function createWeatherReading(array $data): WeatherReading;
    public function listWeatherReadings(array $filters, int $perPage): LengthAwarePaginator;
}
