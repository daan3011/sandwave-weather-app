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

    'providers' => [
        'openweathermap' => [
            'api_url' => env('OPENWEATHERMAP_API_URL') ?? 'http://api.openweathermap.org/data/2.5',
            'api_key' => env('OPENWEATHERMAP_API_KEY'),
        ],
    ],

];
