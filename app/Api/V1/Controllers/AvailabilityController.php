<?php

namespace App\Api\V1\Controllers;

use App\User;
use App\Assignment;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class AvailabilityController extends BaseController
{
    public function email(Request $request, Response $response, $args)
    {
        echo json_encode([
            'is_used' => User::where('email', $args['email'])->first() ? true : false
        ]);

        return $response->withAddedHeader('Content-type', 'application/json');
    }

    public function assignment(Request $request, Response $response, $args)
    {
        echo json_encode([
            'is_assignment' => Assignment::where('title', $args['assignment'])->first() ? true : false,
            'is_request' => \App\Request::withTrashed()->where('title', $args['assignment'])->first() ? true : false
        ]);

        return $response->withAddedHeader('Content-type', 'application/json');
    }
}
