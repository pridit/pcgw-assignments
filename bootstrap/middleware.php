<?php

$app->add('Middleware\Csrf');
$app->add('Middleware\Route');

$app->add($container->get('csrf'));

$app->add(function ($request, $response, $next) use ($container) {
   $this->view->offsetSet("flash", $container['flash']->getMessages());

   return $next($request, $response);
});

$app->add(new \Slim\Middleware\Session([
    'autorefresh' => true,
    'lifetime' => '2 weeks'
]));
