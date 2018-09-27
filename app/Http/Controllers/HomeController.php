<?php

namespace App\Http\Controllers;

use App\Assignment;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'home.twig', [
            'assignments' => Assignment::available()->get(),
            'upcoming' => $this->cache->get('IGDB')
        ]);
    }
}
