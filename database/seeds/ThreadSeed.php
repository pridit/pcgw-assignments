<?php

use App\Thread;

class UserSeed
{
    /**
     * Run the seed.
     *
     * @return void
     */
    function run()
    {
        $thread = new Thread;
        $thread->title = "Welcome";
        $thread->slug = "welcome";
        $thread->is_pinned = 1;
        $thread->save();
    }
}
