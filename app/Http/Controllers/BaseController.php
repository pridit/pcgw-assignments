<?php

namespace App\Http\Controllers;

use Slim\Container;

class BaseController
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Magic getter for access to Slim container.
     *
     * @param string $name
     */
    public function __get($name)
    {
        return $this->container->get($name);
    }
}
