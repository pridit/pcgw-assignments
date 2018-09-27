<?php

namespace App\Helpers;

class Cache
{
    public function __construct($config)
    {
        $this->cache = (object) $config->get('cache.memcached');

        $this->memcached = new \Memcached;
        $this->memcached->addServer($this->cache->ip, $this->cache->port);
    }

    /**
     * Get the cache
     *
     * @param  string $key
     * @return boolean|
     */
    public function get($key)
    {
        if($this->cache->enabled) {
            return $this->memcached->get($key);
        }

        return false;
    }

    /**
     * Set the cache
     *
     * @param string  $key
     * @param mixed   $value
     * @param integer $expiration
     * @return boolean|
     */
    public function set($key, $value, $expiration = 0)
    {
        if($this->cache->enabled) {
            return $this->memcached->set($key, $value, $expiration);
        }

        return false;
    }
}
