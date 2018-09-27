<?php

namespace App\Http\Controllers\Admin;

use App\Application;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class ApplicationController extends BaseController
{
    public function destroy(Request $request, Response $response, $args)
    {
        Application::destroy($args['id']);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
