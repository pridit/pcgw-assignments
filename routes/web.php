<?php

use App\Http\Controllers\{
    HomeController,
    PostController,
    UserController,
    ThreadController,
    RequestController,
    AssignedController,
    GuidelineController,
    AssignmentController,
    ApplicationController
};

use App\Http\Controllers\User\{
    PasswordController,
    SettingsController,
    AuthenticationController
};

$app->get('/', HomeController::class . ':index')->setName('home');

$app->get('/assigned', AssignedController::class . ':index')->setName('assigned.index');
$app->get('/guidelines', GuidelineController::class . ':index')->setName('guidelines.index');

$app->get('/{id:[0-9]+}-{assignment}/apply', AssignmentController::class . ':apply')->setName('assignment.apply')->add($mediawiki);

$app->group('/applications', function () {
    $this->post('', ApplicationController::class . ':store')->setName('applications.store');
});

$app->group('/requests', function () {
    $this->get('', RequestController::class . ':create')->setName('requests.create');
    $this->post('', RequestController::class . ':store')->setName('requests.store');
});

$app->group('/discussions', function () use ($mediawiki) {
    $this->get('', ThreadController::class . ':index')->setName('discussions.index');
    $this->get('/create', ThreadController::class . ':create')->setName('discussions.create')->add($mediawiki);
    $this->post('', ThreadController::class . ':store')->setName('discussions.store')->add($mediawiki);
});

$app->group('/discussions/{id:[0-9]+}-{slug}', function () use ($mediawiki) {
    $this->get('', ThreadController::class . ':show')->setName('discussions.show');
    $this->get('/create', PostController::class . ':create')->setName('posts.create')->add($mediawiki);
    $this->post('', PostController::class . ':store')->setName('posts.store')->add($mediawiki);
});

$app->group('/settings', function () {
    $this->get('', SettingsController::class . ':index')->setName('users.settings.index');
    $this->patch('/{id}', SettingsController::class . ':update')->setName('users.settings.update');
});

$app->group('/users', function () {
    $this->post('', UserController::class . ':store')->setName('users.store');

    $this->group('/password', function () {
        $this->get('', PasswordController::class . ':edit')->setName('users.password.edit');
        $this->patch('', PasswordController::class . ':update')->setName('users.password.update');
    });

    $this->get('/logout', UserController::class . ':logout')->setName('users.logout');
    $this->post('/authenticate', AuthenticationController::class . ':authenticate')->setName('users.authenticate');
    $this->post('/token', UserController::class . ':token')->setName('users.token');
});
