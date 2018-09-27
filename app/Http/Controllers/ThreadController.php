<?php

namespace App\Http\Controllers;

use App\Post;
use App\Thread;
use App\UserSetting;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class ThreadController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        return $this->view->render($response, 'discussions/index.twig', [
            'threads' => Thread::viewable()
                ->structured()
                ->paginate($this->config->get('pagination.discussions.threads'))
        ]);
    }

    public function create(Request $request, Response $response, $args)
    {
        $this->flash->addMessage('thread', true);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function store(Request $request, Response $response, $args)
    {
        $title = $request->getParam('title');

        $thread = Thread::create([
            'title' => ucfirst($title),
            'slug' => $this->slugify->slugify($title)
        ]);

        $post = Post::create([
            'thread_id' => $thread->id,
            'author' => $this->mediawiki->user()->name,
            'body' => $request->getParam('body')
        ]);

        $thread->post_id = $post->id;
        $thread->save();

        if ($this->session->get('user')) {
            $post->user_id = $this->session->get('user')->id;
            $post->save();
        }

        if($this->config->get('mailer.enabled')) {
            foreach (UserSetting::forKey('new-thread')->get() as $setting) {
                $this->pheanstalk->useTube('email')->put(json_encode([
                    'to' => $setting->user->email,
                    'subject' => 'New thread created: ' . $thread->title,
                    'message' => $this->view->fetch('emails/new/discussion/thread.twig', ['thread' => $thread])
                ]));
            }
        }

        return $response->withHeader('Location', $this->router->pathFor('discussions.show', ['id' => $thread->id, 'slug' => $thread->slug]));
    }

    public function show(Request $request, Response $response, $args)
    {
        $thread = Thread::viewable()->find($args['id']);

        if ($thread) {
            return $this->view->render($response, 'discussions/show.twig', [
                'thread' => $thread,
                'posts' => $thread->posts()->paginate($this->config->get('pagination.discussions.posts'))
            ]);
        }

        return $response->withHeader('Location', $this->router->pathFor('discussions.index'));
    }
}
