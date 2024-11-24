<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\WeatherMonitorSeeder;
use Database\Seeders\WeatherReadingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WeatherMonitorSeeder::class,
            WeatherReadingSeeder::class,
        ]);
    }
}
