<?php

namespace App\Helpers;

class Hash
{
    public function password($password)
    {
        return password_hash(
            $password,
            PASSWORD_BCRYPT,
            ['cost' => 10]
        );
    }

    public function passwordCheck($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function hash($input)
    {
        return hash('sha256', $input);
    }

    public function hashCheck($known, $user)
    {
        return hash_equals($known, $user);
    }
}
