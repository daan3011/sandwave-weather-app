<?php

namespace App\Repositories;

use App\Models\WeatherMonitor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Repositories\WeatherMonitorRepositoryInterface;

class WeatherMonitorRepository implements WeatherMonitorRepositoryInterface
{
    public function create(array $data): WeatherMonitor
    {
        return WeatherMonitor::create($data);
    }

    public function find(int $id): ?WeatherMonitor
    {
        return WeatherMonitor::find($id);
    }

    public function update(WeatherMonitor $weatherMonitor, array $data): bool
    {
        return $weatherMonitor->update($data);
    }

    public function delete(WeatherMonitor $weatherMonitor): bool
    {
        return $weatherMonitor->delete();
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return WeatherMonitor::paginate($perPage);
    }

    public function getMonitorsForUpdate(\DateTime $currentTime): Collection
    {
        return WeatherMonitor::where('next_run_at', '<=', $currentTime)
            ->orWhereNull('next_run_at')
            ->get();
    }
}
