<?php

namespace App\Helpers;

use Psr\Http\Message\ServerRequestInterface;

class Cookie
{
    protected $cookies;

    public function __construct($config)
    {
        $this->cookies = (object) $config->get('cookie');
    }

    /**
     * Get the cookie values in the request defined within the config.
     *
     * @return Illuminate\Support\Collection
     */
    public function get(ServerRequestInterface $request)
    {
        $cookies = new \Slim\Http\Cookies($request->getCookieParams());
        
        return collect($this->cookies)->reject(function ($cookie) use ($cookies) {
            return is_null($cookies->get($cookie));
        })->mapWithKeys(function ($cookie) use ($cookies) {
            return [$cookie => $cookies->get($cookie)];
        });
    }
}
