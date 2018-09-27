<?php

use App\Thread;

class ThreadSeed
{
    /**
     * Run the seed.
     *
     * @return void
     */
    function run()
    {
        $thread = new Thread;
        $thread->post_id = 1;
        $thread->title = "Welcome";
        $thread->slug = "welcome";
        $thread->is_pinned = 1;
        $thread->save();
    }
}
