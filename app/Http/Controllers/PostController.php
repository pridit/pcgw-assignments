<?php

namespace App\Http\Controllers;

use App\Post;
use App\Thread;
use App\UserSetting;

use Slim\Http\Request;
use Slim\Http\Response;

use App\Http\Controllers\BaseController;

class PostController extends BaseController
{
    public function create(Request $request, Response $response, $args)
    {
        $thread = Thread::open()->find($args['id']);

        if (!$thread) {
            $this->flash->addMessage('alert', ['error', 'Error', 'You cannot post to a closed thread.']);

            return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
        }

        $this->flash->addMessage('post', ['thread' => $thread]);

        return $response->withHeader('Location', $request->getHeader('HTTP_REFERER'));
    }

    public function store(Request $request, Response $response, $args)
    {
        $thread = Thread::open()->find($args['id']);

        $post = Post::create([
            'thread_id' => $thread->id,
            'body' => $request->getParam('body'),
            'author' => $this->mediawiki->user()->name
        ]);

        if ($this->session->get('user')) {
            $post->user_id = $this->session->get('user')->id;
            $post->save();
        }

        $thread->touch();

        if($this->config->get('mailer.enabled')) {
            foreach (UserSetting::forKey('new-post')->get() as $setting) {
                $this->pheanstalk->useTube('email')->put(json_encode([
                    'to' => $setting->user->email,
                    'subject' => 'New post created in: ' . $post->thread->title,
                    'message' => $this->view->fetch('emails/new/discussion/post.twig', ['post' => $post])
                ]));
            }
        }

        return $response->withHeader('Location', $this->router->pathFor('discussions.show', ['id' => $thread->id, 'slug' => $thread->slug]));
    }
}
