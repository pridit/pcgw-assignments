<?php

namespace App\Http\Controllers;

use App\User;
use App\Token;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    public function store(Request $request, Response $response, $args)
    {
        $user = User::create([
            'first_name' => $request->getParam('first_name'),
            'last_name' => $request->getParam('last_name'),
            'password' => $this->hash->password($request->getParam('password')),
            'email' => $request->getParam('email')
        ]);

        Token::where('hash', $_SESSION['token'])->update([
            'used_by' => $user->id
        ]);

        $this->flash->addMessage('alert', ['success', 'Successful', 'Your account has now been created.']);

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function token(Request $request, Response $response, $args)
    {
        $token = Token::where('hash', $request->getParam('token'));

        if (!$token->isValid()->first()) {
            $this->flash->addMessage('error', ['message' => 'Invalid authorisation token', 'token' => $request->getParam('token')]);

            return $response->withHeader('Location', $this->router->pathFor('home'));
        }

        $_SESSION['token'] = $token->isValid()->first()->hash;

        $this->flash->addMessage('register', true);

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function logout(Request $request, Response $response, $args)
    {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params(); setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_unset();
        session_destroy();

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
