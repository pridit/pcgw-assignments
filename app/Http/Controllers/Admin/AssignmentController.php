<?php

namespace App\Http\Controllers\Admin;

use App\Assign;
use App\Assignment;
use App\Application;
use App\UserSetting;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class AssignmentController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'admin/assignments.twig', [
            'assignments' => Assignment::orderBy('title')->paginate($this->config->get('pagination.assignments.admin'))
        ]);
    }

    public function create(Request $request, Response $response, $args)
    {
        $this->flash->addMessage('assignment', true);

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function edit(Request $request, Response $response, $args)
    {
        $this->flash->addMessage('update', Assignment::where('id', $args['id'])->first());

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function update(Request $request, Response $response, $args)
    {
        $report = $request->getParam('report');

        if(!empty($request->getParam('app_id'))) $appid = $request->getParam('app_id'); else $appid = NULL;
        if(!empty($request->getParam('url'))) $url = $request->getParam('url'); else $url = NULL;
        if(!empty($request->getParam('path_report'))) $path = $request->getParam('path_report'); else $path = NULL;

        if(!$request->getParam('early_access')) $release = $request->getParam('release'); else $release = NULL;
        if(!$request->getParam('no_expiry')) $expiry = $request->getParam('expiry'); else $expiry = NULL;

        Assignment::where('id', $request->getParam('submit'))->update([
            'app_id' => $appid,
            'url' => $url,
            'report' => $report,
            'release_at' => $release,
            'expire_at' => $expiry,
            'path_report' => $path
        ]);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function store(Request $request, Response $response, $args)
    {
        $title = $request->getParam('title');
        $report = $request->getParam('report');

        if(!empty($request->getParam('app_id'))) $appid = $request->getParam('app_id'); else $appid = NULL;
        if(!empty($request->getParam('url'))) $url = $request->getParam('url'); else $url = NULL;

        if(!$request->getParam('early_access')) $release = $request->getParam('release'); else $release = NULL;
        if(!$request->getParam('no_expiry')) $expiry = $request->getParam('expiry'); else $expiry = NULL;

        $insert = Assignment::create([
            'title' => $title,
            'slug' => $this->slugify->slugify($title),
            'app_id' => $appid,
            'url' => $url,
            'report' => $report,
            'release_at' => $release,
            'expire_at' => $expiry
        ]);

        $insert->save();

        $setting = UserSetting::where('user_id', $this->session->get('user')->id)->forKey('send-discord')->first();

        if ($setting && $setting->enabled) {
            $this->discord->send([
                'color' => 3447003,
                'title' => $title,
                'url' => 'http://' . $request->getUri()->getHost() . $this->router->pathFor('assignment.apply', ['id' => $insert->id, 'assignment' => $this->slugify->slugify($title)]),
                'description' => 'New assignment available',
                'image' => [
                    'url' => (!is_null($appid)) ? $this->config->get('path.cover.cdn') . $appid . $this->config->get('path.cover.header') : 'http://' . $request->getUri()->getHost() . '/images/missing.jpg'
                ],
                'footer' => [
                    'text' => 'Accepting applications until ' . ((!is_null($expiry)) ? $expiry : 'assigned')
                ]
            ]);
        }

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function applications(Request $request, Response $response, $args)
    {
        try {
            return $this->view->render($response, 'admin/applications.twig', [
                'assignment' => Assignment::find($args['id'])
            ]);
        } catch(Exception $e) {
            // logger
            dd($e);
        }

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function assigns(Request $request, Response $response, $args)
    {
        try {
            return $this->view->render($response, 'admin/assigns.twig', [
                'assignment' => Assignment::find($args['id'])
            ]);
        } catch(Exception $e) {
            // logger
            dd($e);
        }

        return $response->withHeader('Location', $this->router->pathFor('home'));
    }

    public function open(Request $request, Response $response, $args)
    {
        try {
            Assignment::findOrFail($args['id'])->toggleOpen();
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function complete(Request $request, Response $response, $args)
    {
        try {
            Assignment::findOrFail($args['id'])->toggleComplete();
        } catch(Exception $e) {
            // logger
        }

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }
}
