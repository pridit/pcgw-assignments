<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

Illuminate\Pagination\Paginator::currentPageResolver(function () {
    return isset($_GET['page']) ? $_GET['page'] : 1;
});

// Instantiate the application
$container = new \Slim\Container([
    'settings' => [
        'displayErrorDetails' => env('APP_DEBUG', false),
        'determineRouteBeforeAppMiddleware' => true
    ]
]);

// Set up dependencies
require __DIR__ . '/dependencies.php';

$app = new \Slim\App($container);

// Register middleware
require __DIR__ . '/middleware.php';

// Register filters
require __DIR__ . '/filters.php';

// Register database
require __DIR__ . '/database.php';

// Register routes
require __DIR__ . '/routes.php';
