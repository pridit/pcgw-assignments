<?php

return [
    'mail' => [
        'host' => env('MAIL_HOST', env('MAILGUN_SMTP_SERVER')),
        'username' => env('MAIL_USERNAME', env('MAILGUN_SMTP_LOGIN')),
        'password' => env('MAIL_PASSWORD', env('MAILGUN_SMTP_PASSWORD')),
        'from' => [
            'address' => 'press@pcgamingwiki.com',
            'name' => 'PCGamingWiki Assignments'
        ],

        'port' => env('MAIL_PORT', env('MAILGUN_SMTP_PORT')),
        'encryption' => env('MAIL_ENCRYPTION', 'tls')
    ]
];
