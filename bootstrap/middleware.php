<?php

$app->add('Middleware\Csrf');
$app->add('Middleware\Route');

$app->add($container->get('csrf'));

$app->add(new \Slim\Middleware\Session([
    'autorefresh' => true,
    'lifetime' => '2 weeks'
]));
