<?php

$container['config'] = function () {
    return new Noodlehaus\Config(dirname(__DIR__) . '/config');
};

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(dirname(__DIR__) . '/resources/views', [
        'debug' => env('APP_DEBUG')
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new Twig_Extensions_Extension_Text());
    $view->addExtension(new Cocur\Slugify\Bridge\Twig\SlugifyExtension(Cocur\Slugify\Slugify::create()));

    if ($c['session']->exists('user')) $view->getEnvironment()->addGlobal('session', $c['session']->get('user'));

    $view->getEnvironment()->addGlobal('flash', $c['flash']->getMessages());
    $view->getEnvironment()->addGlobal('path', $c['config']['path']);
    $view->getEnvironment()->addGlobal('notice', $c['config']['notices']);

    $view->getEnvironment()->addGlobal('count', [
      'applicants' => App\Applicant::count(),
      'assignments' => App\Assignment::count(),
      'assigns' => App\Assign::count(),
      'requests' => App\Request::count(),
      'users' => App\User::count(),
    ]);

    return $view;
};

/*
|--------------------------------------------------------------------------
| Dependency Containers - Helpers
|--------------------------------------------------------------------------
*/

$container['cookie'] = function ($c) {
    return new App\Helpers\Cookie($c['config']);
};

$container['dxdiag'] = function ($c) {
    return new App\Helpers\Dxdiag;
};

$container['hash'] = function () {
    return new App\Helpers\Hash;
};

$container['mail'] = function ($c) {
    return new App\Helpers\Mail($c['config']);
};

/*
|--------------------------------------------------------------------------
| Dependency Containers - Services
|--------------------------------------------------------------------------
*/

$container['discord'] = function ($c) {
    return new App\Services\Discord($c['config']);
};

$container['igdb'] = function ($c) {
    return new App\Services\IGDB($c['config']);
};

$container['mediawiki'] = function ($c) {
    return new App\Services\MediaWiki($c['cookie']->get($c['request']), $c['logger']);
};

/*
|--------------------------------------------------------------------------
| Dependency Containers - Middleware
|--------------------------------------------------------------------------
*/

$container['Middleware\Csrf'] = function ($c) {
    return function ($request, $response, $next) use ($c) {
        $c['view']->getEnvironment()->addGlobal('csrf_fields',
            sprintf(
                '<input name="csrf_name" type="hidden" value="%s" />
                <input name="csrf_value" type="hidden" value="%s" />',
                $request->getAttribute($c['csrf']->getTokenNameKey()),
                $request->getAttribute($c['csrf']->getTokenValueKey())
            )
        );

        return $next($request, $response);
    };
};

$container['Middleware\Route'] = function($c) {
    return function ($request, $response, $next) use ($c) {
        if ($request->getAttribute('route')) {
            $c['view']->getEnvironment()->addGlobal('route', $request->getAttribute('route')->getName());
        }

        return $next($request, $response);
    };
};

/*
|--------------------------------------------------------------------------
| Dependency Containers - Handlers
|--------------------------------------------------------------------------
*/

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        $c['view']->getEnvironment()->addGlobal('error', true);

        return $c['view']->render($response, 'templates/errors/404.twig')->withStatus(404);
    };
};

$container['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $c['logger']->error($request->getUri(), [$exception]);
        
        if (env('APP_ENV') == "production") {
            return $c['view']->render($response, 'templates/errors/500.twig')->withStatus(500);
        }
        
        return dd($exception);
    };
};

/*
|--------------------------------------------------------------------------
| Dependency Containers - Third-party
|--------------------------------------------------------------------------
*/

$container['cronitor'] = function ($c) {
    return new \Cronitor\Client(
        $c['config']['api']['cronitor']['monitor'],
        $c['config']['api']['cronitor']['authkey']
    );
};

$container['csrf'] = function ($c) {
    $csrf = new Slim\Csrf\Guard;

    $csrf->setPersistentTokenMode(true);

    return $csrf;
};

$container['flash'] = function () {
    return new Slim\Flash\Messages();
};

$container['memcached'] = function ($c) {
    $memcached = new \Memcached;
    $memcached->addServer(
        $c['config']['cache']['memcached']['ip'],
        $c['config']['cache']['memcached']['port']
    );
    
    return $memcached;
};

$container['pheanstalk'] = function () {
    return new Pheanstalk\Pheanstalk('127.0.0.1');
};

$container['session'] = function ($c) {
  return new SlimSession\Helper;
};

$container['slugify'] = function () {
    return new Cocur\Slugify\Slugify();
};

$container['validator'] = function () {
    return new Awurth\SlimValidation\Validator();
};

/*
|--------------------------------------------------------------------------
| Dependency Containers - Logging
|--------------------------------------------------------------------------
*/

$container['logger'] = function ($c) {
    $settings = $c['config']['logger'];

    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path']));

    return $logger;
};
