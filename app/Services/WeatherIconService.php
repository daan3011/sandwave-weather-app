<?php
namespace App\Services;

use App\Interfaces\Services\WeatherIconServiceInterface;

class WeatherIconService implements WeatherIconServiceInterface
{
    protected array $mapping;

    public function __construct()
    {
        $this->mapping = config('weather_icons');
    }

    public function getIcon(?int $weatherCode): string
    {
        foreach ($this->mapping as $group) {
            if (in_array($weatherCode, $group['codes'], true)) {
                return $group['icon'];
            }
        }

        return '☁️';
    }
}
