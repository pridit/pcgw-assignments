<?php

use Crunz\Schedule;

require_once __DIR__ . '/../../vendor/autoload.php';

if (env('APP_ENV') == "development") {
    $dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
    $dotenv->load();
}

// Instantiate the application
$container = new \Slim\Container();

// Set up dependencies
require __DIR__ . '/../../bootstrap/dependencies.php';

$schedule = new Schedule();
