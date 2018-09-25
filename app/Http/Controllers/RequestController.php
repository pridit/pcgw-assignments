<?php

namespace App\Http\Controllers;

use App\UserSetting;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class RequestController extends BaseController
{
    public function create(Request $request, Response $response, $args)
    {
        $this->flash->addMessage('request', true);

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function store(Request $request, Response $response, $args)
    {
        $request = \App\Request::create($request->getParams());

        if ($this->config->get('mailer.enabled')) {
            foreach (UserSetting::forKey('new-request')->get() as $setting) {
                $this->pheanstalk->useTube('email')->put(json_encode([
                    'to' => $setting->user->email,
                    'subject' => 'New request for ' . $request->title,
                    'message' => $this->view->fetch('emails/new/request.twig', ['assignment' => $request])
                ]));
            }
        }

        $this->flash->addMessage('alert', ['success', 'Successful', 'Your request has been submitted.']);

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }
}
