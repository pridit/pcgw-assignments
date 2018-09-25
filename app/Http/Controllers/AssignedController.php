<?php

namespace App\Http\Controllers;

use App\Assign;
use App\Assignment;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class AssignedController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'assigned.twig', [
            'assignments' => Assignment::has('assigns')->orderBy('title')->get()
        ]);
    }
}
