<?php
namespace App\Interfaces\Repositories;

use App\Models\WeatherMonitor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WeatherMonitorRepositoryInterface
{
    public function create(array $data): WeatherMonitor;
    public function find(int $id): ?WeatherMonitor;
    public function update(WeatherMonitor $weatherMonitor, array $data): bool;
    public function delete(WeatherMonitor $weatherMonitor): bool;
    public function getAll(int $perPage = 15): LengthAwarePaginator;
    public function getMonitorsForUpdate(\DateTime $currentTime): Collection;
}
