<?php

namespace App\Helpers;

use Slim\Http\Request;

class Cookie
{
    protected $request;
    protected $cookies;

    public function __construct(Request $request, $config)
    {
        $this->request = $request;
        $this->cookies = (object) $config->get('cookie');
    }

    /**
     * Get the cookie values defined within the config.
     *
     * @return Illuminate\Support\Collection
     */
    public function get()
    {
        $cookies = new \Slim\Http\Cookies($this->request->getCookieParams());

        $cookies = collect($this->cookies)->reject(function ($cookie) use ($cookies) {
            return is_null($cookies->get($cookie));
        })->mapWithKeys(function ($cookie) use ($cookies) {
            return [$cookie => $cookies->get($cookie)];
        });

        return $cookies;
    }
}
