<?php

namespace App\Http\Controllers\Admin;

use App\Thread;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class ThreadController extends BaseController
{
    public function destroy(Request $request, Response $response, $args)
    {
        Thread::destroy($args['id']);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function pin(Request $request, Response $response, $args)
    {
        try {
            Thread::findOrFail($args['id'])->togglePinned();
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function closed(Request $request, Response $response, $args)
    {
        try {
            Thread::findOrFail($args['id'])->toggleClosed();
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
