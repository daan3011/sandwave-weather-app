<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Weather api's
    |--------------------------------------------------------------------------
    |
    | Here you may specify the weather api providers
    |
    */

    "weather_overview_cache_ttl" => env('WEATHER_OVERVIEW_CACHE_TTL', 5),

    'providers' => [
        'openweathermap' => [
            'api_url' => env('OPENWEATHERMAP_API_URL', 'http://api.openweathermap.org/data/2.5'),
            'geo_api_url' => env('OPENWEATHERMAP_GEO_API_URL', 'http://api.openweathermap.org/geo/1.0'),
            'api_key' => env('OPENWEATHERMAP_API_KEY', ""),
        ],
    ],

];
