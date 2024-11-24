<?php
namespace App\Interfaces\Services;

interface WeatherIconServiceInterface
{
    public function getIcon(int $weatherCode): string;
}
