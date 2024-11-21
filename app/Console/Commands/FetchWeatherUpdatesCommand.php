<?php

namespace App\Console\Commands;

use App\Models\WeatherMonitor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use App\Interfaces\Repositories\WeatherMonitorRepositoryInterface;
use App\Interfaces\Repositories\WeatherReadingRepositoryInterface;

class FetchWeatherUpdatesCommand extends Command
{
    protected $signature = 'weather:fetch-updates';
    protected $description = 'Fetch weather updates for all monitors';

    protected $weatherService;
    protected $weatherMonitorRepository;
    protected $weatherReadingRepository;

    public function __construct(
        OpenWeatherMapServiceInterface $weatherService,
        WeatherMonitorRepositoryInterface $weatherMonitorRepository,
        WeatherReadingRepositoryInterface $weatherReadingRepository
    ) {
        parent::__construct();
        $this->weatherService = $weatherService;
        $this->weatherMonitorRepository = $weatherMonitorRepository;
        $this->weatherReadingRepository = $weatherReadingRepository;
    }

    public function handle()
    {
        $now = now();

        $monitors = $this->weatherMonitorRepository->getMonitorsForUpdate($now);

        foreach ($monitors as $monitor) {
            $this->fetchAndStoreWeatherData($monitor);

            // Update next_run_at
            $monitor->next_run_at = $now->addMinutes($monitor->interval_minutes);
            $monitor->save();
        }
    }

    protected function fetchAndStoreWeatherData(WeatherMonitor $monitor) : void
    {
        try {
            $weatherData = $this->weatherService->getCurrentWeather($monitor->city);
            $processedData = $this->weatherService->extractWeatherData($weatherData);

            $this->weatherReadingRepository->create([
                'weather_monitor_id'  => $monitor->id,
                'city'                => $monitor->city,
                'temperature'         => $processedData['temperature'],
                'feels_like'          => $processedData['feels_like'],
                'weather_description' => $processedData['weather_description'],
                'wind_speed'          => $processedData['wind_speed'],
                'wind_direction'      => $processedData['wind_direction'],
                'chance_of_rain'      => $processedData['chance_of_rain'],
                'recorded_at'         => $processedData['recorded_at'],
            ]);

            $this->info("Weather data fetched for {$monitor->city}.");
        } catch (\Exception $e) {
            Log::error("Failed to fetch weather data for {$monitor->city}: {$e->getMessage()}");
        }
    }
}
