<?php
namespace App\Interfaces\Repositories;

use App\Models\WeatherReading;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WeatherReadingRepositoryInterface
{
    public function create(array $data): WeatherReading;
    public function getAll(int $perPage = 15): LengthAwarePaginator;
}
