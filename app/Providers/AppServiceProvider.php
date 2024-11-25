<?php

namespace App\Providers;

use App\Services\WeatherIconService;
use App\Services\OpenWeatherMapService;
use App\Services\WeatherMonitorService;
use App\Services\WeatherReadingService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\WeatherMonitorRepository;
use App\Repositories\WeatherReadingRepository;
use App\Services\CachingOpenWeatherMapService;
use Illuminate\Contracts\Foundation\Application;
use App\Interfaces\Services\WeatherIconServiceInterface;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use App\Interfaces\Services\WeatherMonitorServiceInterface;
use App\Interfaces\Services\WeatherReadingServiceInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use App\Interfaces\Repositories\WeatherMonitorRepositoryInterface;
use App\Interfaces\Repositories\WeatherReadingRepositoryInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Services
        $this->app->bind(OpenWeatherMapServiceInterface::class, OpenWeatherMapService::class);

        $this->app->extend(OpenWeatherMapServiceInterface::class, function (OpenWeatherMapServiceInterface $service, Application $app) {
            return new CachingOpenWeatherMapService($service, $app->make(CacheRepository::class));
        });
        $this->app->bind(WeatherMonitorServiceInterface::class, WeatherMonitorService::class);
        $this->app->bind(WeatherReadingServiceInterface::class, WeatherReadingService::class);
        $this->app->bind(WeatherIconServiceInterface::class, WeatherIconService::class);

        // Repositories
        $this->app->bind(WeatherMonitorRepositoryInterface::class, WeatherMonitorRepository::class);
        $this->app->bind(WeatherReadingRepositoryInterface::class, WeatherReadingRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
