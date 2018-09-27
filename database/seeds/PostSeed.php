<?php

use App\Post;

class PostSeed
{
    /**
     * Run the seed.
     *
     * @return void
     */
    function run()
    {
        $post = new Post;
        $post->thread_id = 1;
        $post->author = 'Pridit';
        $post->body = 'Hey there, thanks so much for checking out the repo and getting a working project up and running. This is an example thread, which can be removed. Have fun!';
        $post->save();
    }
}
