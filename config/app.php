<?php

return [
    'pagination' => [
        'applicants'    => 50,
        'assigned'      => 80,
        'users'         => 10,

        'assignments' => [
            'web'       => 5,
            'admin'     => 50
        ],

        'discussions' => [
            'threads'   => 8,
            'posts'     => 10
        ]
    ],

    'notices' => [
        'home' => '
            In exchange for filling out a <span class="highlight highlight-wiki">Wiki</span> article and/or
            writing a PC <span class="highlight highlight-report">Report</span>, we offer a full, entirely
            <strong>free</strong> copy of the game to enable you to do it
        ',

        'discussions' => 'Do you have a question or concern regarding assignments? Want to discuss with fellow contributors? Need help or guidance?'
    ],

    'mailer' => [
        'enabled' => env('MAILER_ENABLED')
    ],

    'csrf' => [
        'key' => 'csrf_token'
    ],

    'logger' => [
        'name' => 'pcgw',
        'path' => dirname(__DIR__) . "/storage/logs/app.log"
    ]
];
