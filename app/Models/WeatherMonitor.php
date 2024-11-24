<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeatherMonitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'country',
        'latitude',
        'longitude',
        'interval_minutes',
        'next_run_at',
    ];

    protected $casts = [
        'next_run_at' => 'datetime',
    ];

    public function weatherReadings() : HasMany
    {
        return $this->hasMany(WeatherReading::class);
    }
}
