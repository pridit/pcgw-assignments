<?php

use App\Api\V1\Controllers\{
    AvailabilityController
};

$app->group('/api', function () {
    $this->group('/v1', function () {
        $this->get('/email/{email}', AvailabilityController::class . ':email')->setName('email.check');
        $this->get('/assignment/{assignment}', AvailabilityController::class . ':assignment')->setName('assignment.check');
    });
});
