<?php

namespace App\Repositories;

use App\Interfaces\Repositories\WeatherReadingRepositoryInterface;
use App\Models\WeatherReading;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WeatherReadingRepository implements WeatherReadingRepositoryInterface
{
    public function create(array $data): WeatherReading
    {
        return WeatherReading::create($data);
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return WeatherReading::paginate($perPage);
    }
}
