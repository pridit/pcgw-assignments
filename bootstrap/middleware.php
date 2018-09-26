<?php

$app->add('Middleware\Csrf');
$app->add('Middleware\Route');

$app->add($container->get('csrf'));

$app->add(new \Slim\Middleware\Session([
    'lifetime' => '2 weeks',
    'autorefresh' => true,
    'ini_settings' => [
        'session.save_path'      => realpath(dirname($_SERVER['DOCUMENT_ROOT'])) . '/storage/sessions',
        'session.gc_maxlifetime' => 604800,
    ]
]));
