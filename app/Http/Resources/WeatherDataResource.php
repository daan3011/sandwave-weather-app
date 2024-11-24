<?php

namespace App\Http\Resources;

use App\Interfaces\Services\WeatherIconServiceInterface;

class WeatherDataResource extends BaseResource
{
    protected WeatherIconServiceInterface $weatherIconService;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->weatherIconService = $this->resolveService(WeatherIconServiceInterface::class);
    }

    public function toArray($request)
    {
        return [
            'city'                => $this->resource['city'],
            'weather_description' => $this->resource['current_weather']['weather'][0]['description'] ?? null,
            'current_temperature' => $this->resource['current_weather']['main']['temp'] ?? null,
            'todays_forecast'     => $this->formatTodaysForecast($this->resource['forecast']),
            'air_conditions'      => $this->formatAirConditions($this->resource['air_conditions']),
            'five_day_forecast'   => $this->formatFiveDayForecast($this->resource['forecast']),
        ];
    }

    protected function formatTodaysForecast($forecast)
    {
        $today = date('Y-m-d');
        $todaysForecast = array_values(array_filter($forecast['list'], function ($item) use ($today) {
            return strpos($item['dt_txt'], $today) === 0;
        }));

        return array_map(function ($item) {
            $weatherCode = $item['weather'][0]['id'] ?? null;
            return [
                'time'        => date('ga', strtotime($item['dt_txt'])),
                'temperature' => round($item['main']['temp']) . '°C',
                'icon'        => $this->weatherIconService->getIcon($weatherCode),
                'description' => $item['weather'][0]['description'],
            ];
        }, $todaysForecast);
    }

    protected function formatAirConditions($airConditions)
    {
        $components = $airConditions['list'][0]['components'] ?? [];

        return [
            [
                'label' => 'CO',
                'value' => $components['co'] ?? 'N/A',
            ],
            [
                'label' => 'NO',
                'value' => $components['no'] ?? 'N/A',
            ],
            [
                'label' => 'NO₂',
                'value' => $components['no2'] ?? 'N/A',
            ],
            [
                'label' => 'O₃',
                'value' => $components['o3'] ?? 'N/A',
            ],
        ];
    }

    protected function formatFiveDayForecast($forecast)
    {
        $dailyForecasts = [];

        foreach ($forecast['list'] as $item) {
            $date = date('Y-m-d', strtotime($item['dt_txt']));
            $dayName = date('l', strtotime($item['dt_txt']));

            if (!isset($dailyForecasts[$date])) {
                $weatherCode = $item['weather'][0]['id'] ?? null;
                $dailyForecasts[$date] = [
                    'day'          => $dayName,
                    'temperatures' => [],
                    'weather'      => ucfirst($item['weather'][0]['description']),
                    'icon'         => $this->weatherIconService->getIcon($weatherCode),
                ];
            }

            $dailyForecasts[$date]['temperatures'][] = $item['main']['temp'];
        }

        // Limit to 5 days
        $dailyForecasts = array_slice($dailyForecasts, 0, 5);

        // Calculate high/low temperatures
        $formattedForecasts = [];

        foreach ($dailyForecasts as $date => $data) {
            $highTemp = round(max($data['temperatures'])) . '°C';
            $lowTemp = round(min($data['temperatures'])) . '°C';

            $formattedForecasts[] = [
                'day'          => $data['day'],
                'weather'      => $data['weather'],
                'icon'         => $data['icon'],
                'high_low'     => "{$highTemp}/{$lowTemp}",
            ];
        }

        return $formattedForecasts;
    }
}
