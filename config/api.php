<?php

return [
    'api' => [
        'igdb' => [
            'key' => env('IGDB_KEY'),
            'endpoint'  => env('IGDB_ENDPOINT'),

            'parameters' => [
                'fields'    => ['name', 'first_release_date', 'release_dates.date', 'release_dates.category', 'release_dates.platform', 'release_dates.region', 'developers', 'publishers'],
                'platform'  => 6,
                'region'    => [1,8],
                'hypes'     => 1,
                'order'     => 'first_release_date,name',
                'limit'     => 12
            ]
        ],

        'cronitor' => [
            'monitor' => env('CRONITOR_MONITOR'),
            'authkey' => env('CRONITOR_AUTHKEY')
        ]
    ]
];
