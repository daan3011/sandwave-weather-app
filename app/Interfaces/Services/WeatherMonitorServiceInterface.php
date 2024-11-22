<?php

namespace App\Interfaces\Services;

use App\Models\WeatherMonitor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WeatherMonitorServiceInterface
{
    public function createWeatherMonitor(array $data): WeatherMonitor;
    public function getWeatherMonitor(int $id): ?WeatherMonitor;
    public function deleteWeatherMonitor(WeatherMonitor $weatherMonitor): bool;
    public function listWeatherMonitors(int $perPage = 15): LengthAwarePaginator;
}
