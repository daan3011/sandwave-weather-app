<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FetchWeatherReadingJob;
use App\Interfaces\Repositories\WeatherMonitorRepositoryInterface;

class FetchWeatherUpdatesCommand extends Command
{
    protected $signature = 'weather:fetch-updates';
    protected $description = 'Fetch weather updates for all monitors';

    protected $weatherMonitorRepository;

    public function __construct(
        WeatherMonitorRepositoryInterface $weatherMonitorRepository
    ) {
        parent::__construct();
        $this->weatherMonitorRepository = $weatherMonitorRepository;
    }

    public function handle()
    {
        $now = now();

        $monitors = $this->weatherMonitorRepository->getMonitorsForUpdate($now);

        foreach ($monitors as $monitor) {
            FetchWeatherReadingJob::dispatch($monitor->id);
        }
    }
}
