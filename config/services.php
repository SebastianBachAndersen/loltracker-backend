<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'riot_games_api' => [
        'api_key' => env('RIOT_GAMES_API_KEY'),
        'url' => env('RIOT_GAMES_API_URL'),
        'servers' => [
            'euw' => 'euw1',
            'br' => 'br1',
            'eune' => 'eun1',
            'lan' => 'la1',
            'las' => 'la2',
            'na' => 'na1',
            'oce' => 'oc1',
            'ru' => 'ru',
            'tr' => 'tr1',
            'jp' => 'jp1'
        ],
        'regions' => [
            'europe' => ['euw', 'eune', 'tr', 'ru'],
            'americas' => ['na', 'br', 'lan', 'las', 'oce'],
            'asia' => ['kr', 'jp']
        ]
    ]

];
