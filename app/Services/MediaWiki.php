<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

use SlimSession\Helper;

class MediaWiki
{
    protected $cookies;
    protected $logger;

    public function __construct($cookies, $logger)
    {
        $this->cookies = $cookies;
        $this->logger = $logger;
    }

    /**
     * Send a request to the MediaWiki API.
     *
     * @param array $params
     * @return object
     */
    protected function request($query, $cookies = null)
    {
        $client = new Client();
        
        $query['action'] = 'query';
        $query['format'] = 'json';
        
        $response = $client->request('GET', 'https://pcgamingwiki.com/w/api.php', [
            'query' => $query,
            'cookies' => $cookies ?: ''
        ]);
        
        $content = json_decode($response->getBody()->getContents());
        
        return $content->query;
    }

    /**
     * Authenticate given user based on cookies.
     *
     * @return boolean|object
     */
    public function authenticate()
    {
        $session = new Helper;
        
        if ($session->exists('mediawiki')) {
            return $session->get('mediawiki');
        }
        
        if ($this->cookies->isEmpty()) {
            return false;
        }
        
        $response = self::request(
            ['meta' => 'userinfo'],
            CookieJar::fromArray($this->cookies->toArray(), '.pcgamingwiki.com')
        );
        
        $userinfo = $response->userinfo;

        if (isset($userinfo->anon)) {
            return false;
        }
        
        $user = $this->user($userinfo->name);

        if (isset($user->blockid)) {
            return false;
        }

        $this->logger->info(sprintf('Authenticated with MediaWiki: %s', $user->name));
        
        $session->set('mediawiki', $user);

        return $session->get('mediawiki');
    }

    /**
     * Retrieve the data for a user.
     *
     * @param string $user
     * @return boolean|object
     */
    public function user($name) {
        $response = self::request([
            'list' => 'users',
            'ususers' => $name,
            'usprop' => 'editcount|registration|blockinfo'
        ]);
        
        $user = $response->users[0];
        
        return !isset($user->missing) ? $user : false;
    }
    
    /**
     * Determine whether an article exists for a page.
     *
     * @param  string $name
     * @return boolean
     */
    public function isArticle($name) {
        $response = self::request(['titles' => $name]);
        
        return !isset(current($response->pages)->missing);
    }
}
