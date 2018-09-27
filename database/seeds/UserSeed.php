<?php

use App\User;
use App\Helpers\Hash;

class UserSeed
{
    /**
     * Run the seed.
     *
     * @return void
     */
    function run()
    {
        $user = new User;
        $user->first_name = "John";
        $user->last_name = "Doe";
        $user->username = "Admin";
        $user->password = (new Hash)->password('123');
        $user->email = "john@doe.com";
        $user->save();
    }
}
