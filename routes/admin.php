<?php

use App\Http\Controllers\Admin\{
    PostController,
    UserController,
    TokenController,
    ThreadController,
    AssignController,
    RequestController,
    ApplicantController,
    AssignmentController,
    ApplicationController
};

$app->group('/admin', function () {
    $this->group('/applicants', function () {
        $this->get('', ApplicantController::class . ':index')->setName('admin.applicants.index');
        $this->delete('/{id}', ApplicantController::class . ':destroy')->setName('admin.applicants.destroy');
    });

    $this->group('/applicants/{id}', function () {
        $this->get('/assigned', ApplicantController::class . ':assigned')->setName('admin.applicants.assigned');
        $this->get('/applied', ApplicantController::class . ':applied')->setName('admin.applicants.applied');
        $this->get('/dxdiag', ApplicantController::class . ':dxdiag')->setName('admin.applicants.dxdiag');

        $this->post('/assign', ApplicantController::class . ':assign')->setName('admin.applications.assign');
        $this->post('/unassign', ApplicantController::class . ':unassign')->setName('admin.applications.unassign');

        $this->patch('/blacklist', ApplicantController::class . ':blacklist')->setName('admin.applicants.blacklist');
    });

    $this->group('/applications', function () {
        $this->delete('/{id}', ApplicationController::class . ':destroy')->setName('admin.applications.destroy');
    });

    $this->group('/assignments', function () {
        $this->get('', AssignmentController::class . ':index')->setName('admin.assignments.index');
        $this->get('/create', AssignmentController::class . ':create')->setName('admin.assignments.create');
        $this->post('', AssignmentController::class . ':store')->setName('admin.assignments.store');
        $this->get('/{id}/edit', AssignmentController::class . ':edit')->setName('admin.assignments.edit');
        $this->patch('', AssignmentController::class . ':update')->setName('admin.assignments.update');
    });

    $this->group('/assignments/{id}', function () {
        $this->get('/applications', AssignmentController::class . ':applications')->setName('admin.assignments.applications');
        $this->get('/assigns', AssignmentController::class . ':assigns')->setName('admin.assignments.assigns');

        $this->patch('/open', AssignmentController::class . ':open')->setName('admin.assignments.open');
        $this->patch('/complete', AssignmentController::class . ':complete')->setName('admin.assignments.complete');
    });

    $this->group('/posts', function () {
        $this->delete('/{id}', PostController::class . ':destroy')->setName('admin.posts.destroy');
    });

    $this->group('/requests', function () {
        $this->get('', RequestController::class . ':index')->setName('admin.requests.index');
        $this->delete('/{id}', RequestController::class . ':destroy')->setName('admin.requests.destroy');
    });

    $this->group('/threads', function () {
        $this->delete('{id}', ThreadController::class . ':destroy')->setName('admin.threads.destroy');
    });

    $this->group('/threads/{id}', function () {
        $this->patch('/pin', ThreadController::class . ':pin')->setName('admin.threads.pin');
        $this->patch('/close', ThreadController::class . ':closed')->setName('admin.threads.close');
    });

    $this->group('/tokens', function () {
        $this->get('', TokenController::class . ':index')->setName('admin.tokens.index');
        $this->post('', TokenController::class . ':store')->setName('admin.tokens.store');
        $this->delete('/{id}', TokenController::class . ':destroy')->setName('admin.tokens.destroy');
    });

    $this->group('/users', function () {
        $this->get('', UserController::class . ':index')->setName('admin.users.index');
    });

    $this->group('/users/{id}', function () {
        $this->patch('/disable', UserController::class . ':disable')->setName('admin.users.disable');
    });
})->add($authenticated);
