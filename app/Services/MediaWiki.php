<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

use SlimSession\Helper;

class MediaWiki
{
    protected $cookies;

    public function __construct($cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * Send a request to the MediaWiki API with relevant cookies.
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    protected function request()
    {
        $client = new Client();

        return $client->request('GET', 'https://pcgamingwiki.com/w/api.php', [
            'query' => ['action' => 'query', 'meta' => 'userinfo', 'format' => 'json'],
            'cookies' => CookieJar::fromArray($this->cookies->toArray(), '.pcgamingwiki.com')
        ]);
    }

    /**
     * Get the user object associated with the JSON response of the request.
     *
     * @return boolean|object
     */
    public function user()
    {
        $session = new Helper;

        if ($session->exists('mediawiki')) {
            return $session->get('mediawiki');
        }

        if ($this->cookies->isEmpty()) {
            return false;
        }

        $response = json_decode($this::request()->getBody()->getContents());
        $userinfo = $response->query->userinfo;

        if (isset($userinfo->anon)) {
            return false;
        }

        $session->set('mediawiki', $userinfo);

        return $session->get('mediawiki');
    }
}
