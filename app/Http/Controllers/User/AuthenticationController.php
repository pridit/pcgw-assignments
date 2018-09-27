<?php

namespace App\Http\Controllers\User;

use App\User;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class AuthenticationController extends BaseController
{
    public function authenticate(Request $request, Response $response, $args)
    {
        $password = $request->getParam('password');

        $user = User::where('email', $request->getParam('email'))->first();

        if($user->disabled) {
            $this->flash->addMessage('alert', ['error', 'Disabled', 'Your account has been disabled.']);

            return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
        }

        if(!$user || !$this->hash->passwordCheck($password, $user->password)) {
            $this->flash->addMessage('alert', ['error', 'Invalid', 'Invalid email address or password.']);

            return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
        }

        $user->touch();

        $this->session->set('user', new User($user->toArray()));

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
