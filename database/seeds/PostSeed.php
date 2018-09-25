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
        $post->author = 'Pridit';
        $post->body = 'Hey there, thanks so much for checking out the repo and getting a working project up and running.\r\nThis is an example thread, which can be removed.\r\nHave fun!'
        $post->save();
    }
}
