<?php

namespace App\Http\Controllers\Admin;

use App\Post;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class PostController extends BaseController
{
    public function destroy(Request $request, Response $response, $args)
    {
        Post::destroy($args['id']);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
