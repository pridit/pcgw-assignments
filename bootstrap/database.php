<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (env('APP_ENV') == "development") {
    $dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
    $dotenv->load();
}

$url = [];

if (in_array(env('APP_ENV'), ['production', 'staging'])) {
    $url = parse_url(env('JAWSDB_URL'));
};

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => env('DB_HOST', $url['host']),
    'port'      => env('DB_PORT', $url['port']),
    'database'  => env('DB_DATABASE', ltrim($url['path'], '/')),
    'username'  => env('DB_USERNAME', $url['user']),
    'password'  => env('DB_PASSWORD', $url['pass']),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci'
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
