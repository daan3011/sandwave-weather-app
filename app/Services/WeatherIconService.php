<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Interfaces\Services\WeatherIconServiceInterface;

class WeatherIconService implements WeatherIconServiceInterface
{
    /**
     * @var array
     */
    protected array $mapping;

    /**
     * WeatherIconService constructor.
     */
    public function __construct()
    {
        $this->mapping = config('weather_icons');
    }

    /**
     * Retrieves the corresponding weather icon for the given weather code.
     *
     * @param int|null $weatherCode The weather code for which to fetch an icon.
     *
     * @return string The icon representing the weather condition, or a default icon if no match is found.
     */
    public function getIcon(?int $weatherCode): string
    {
        foreach ($this->mapping as $group) {
            if (in_array($weatherCode, $group['codes'], true)) {
                return $group['icon'];
            }
        }

        Log::warning("No icon found for weather code: $weatherCode");
        return '☁️';
    }
}
