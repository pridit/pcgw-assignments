<?php

namespace App\Http\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class GuidelineController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'guidelines.twig');
    }
}
