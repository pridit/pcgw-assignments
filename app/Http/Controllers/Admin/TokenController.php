<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Token;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class TokenController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $this->flash->addMessage('tokens', ['tokens' => User::find($this->session->get('user')->id)->tokens]);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function store(Request $request, Response $response, $args)
    {
        Token::create([
            'user_id' => $this->session->get('user')->id,
            'hash' => substr(uniqid(rand(), true), 0, 15)
        ]);

        return $response->withHeader('Location', $this->router->pathFor('admin.tokens.index'));
    }

    public function destroy(Request $request, Response $response, $args)
    {
        Token::find($args['id'])->delete();

        return $response->withHeader('Location', $this->router->pathFor('admin.tokens.index'));
    }
}
