<?php

namespace App\Http\Controllers;

use App\Assignment;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class AssignmentController extends BaseController
{
    public function apply(Request $request, Response $response, $args)
    {
        try {
            return $this->view->render($response, 'apply.twig', [
                'assignment' => Assignment::findOrFail($args['id'])
            ]);
        } catch (Exception $e) {
            $this->logger->error($e);

            $this->flash->addMessage('alert', ['error', 'Something went wrong', 'Not to fear, we have logged the issue and will look into it. Please try again later.']);
        }

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }
}
