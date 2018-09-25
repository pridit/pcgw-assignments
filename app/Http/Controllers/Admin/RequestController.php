<?php

namespace App\Http\Controllers\Admin;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class RequestController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'admin/requests.twig', [
            'requests' => \App\Request::orderBy('release_at')->get()
        ]);
    }

    public function destroy(Request $request, Response $response, $args)
    {
        \App\Request::destroy($args['id']);

        return $response->withHeader('Location', $this->router->pathFor('admin.requests.index'));
    }
}
