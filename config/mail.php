<?php

return [
    'mail' => [
        'host' => env('MAIL_HOST'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'from' => [
            'address' => 'press@pcgamingwiki.com',
            'name' => 'PCGamingWiki Assignments'
        ],

        'port' => env('MAIL_PORT'),
        'encryption' => ENV('MAIL_ENCRYPTION', 'tls')
    ]
];
