<?php

namespace App\Http\Controllers\User;

use App\User;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class PasswordController extends BaseController
{
    public function edit(Request $request, Response $response, $args)
    {
        $this->flash->addMessage('password', true);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function update(Request $request, Response $response, $args)
    {
        User::find($this->session->get('user')->id)->update([
            'password' => $this->hash->password($request->getParam('password_new'))
        ]);

        $this->flash->addMessage('alert', ['success', 'Successful', 'Your password has been successfully changed.']);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
