<?php

namespace App\Repositories;

use App\Models\WeatherReading;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Interfaces\Repositories\WeatherReadingRepositoryInterface;

class WeatherReadingRepository implements WeatherReadingRepositoryInterface
{
    public function create(array $data): WeatherReading
    {
        return WeatherReading::create($data);
    }

    public function getAll(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = WeatherReading::query();

        if (isset($filters['weather_monitor_id'])) {
            $query->where('weather_monitor_id', $filters['weather_monitor_id']);
        }

        if (isset($filters['start_date'])) {
            $query->where('recorded_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('recorded_at', '<=', $filters['end_date']);
        }

        $query->addSelect([
            'city',
            'temperature',
            'feels_like',
            'weather_description',
            'wind_speed',
            'wind_direction',
            'chance_of_rain',
            'weather_code',
            'recorded_at',
        ]);

        return $query->orderBy('recorded_at', 'desc')->paginate($perPage);
    }

}
