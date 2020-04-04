<?php

$url = [];

if (in_array(env('APP_ENV'), ['production', 'staging'])) {
    $url = parse_url(env('MEMCACHEDCLOUD_SERVERS'));
};

return [
    'cache' => [
        'memcached' => [
            'enabled'   => true,
            'ip'        => env('MEMCACHED_IP', $url['host']),
            'port'      => env('MEMCACHED_PORT', $url['port']),
            'timeout'   => 5
        ]
    ]
];
