<?php

namespace App\Http\Controllers\Admin;

use App\User;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'admin/users.twig', [
            'users' => User::paginate($this->config->get('pagination.users'))
        ]);
    }

    public function disable(Request $request, Response $response, $args)
    {
        try {
            User::findOrFail($args['id'])->toggleDisabled();
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
