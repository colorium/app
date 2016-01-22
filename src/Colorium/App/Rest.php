<?php

namespace Colorium\App;

use Colorium\Routing\Contract\RouterInterface;

class Rest extends Kernel
{

    /**
     * Rest app constructor
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router = null)
    {
        parent::__construct(
            new Kernel\Catching,
            new Kernel\Routing($router),
            new Kernel\Rendering
        );
    }


    /**
     * Set route
     *
     * @param string $query
     * @param callable $resource
     * @return $this
     */
    public function on($query, $resource)
    {
        $this->config->router->add($query, $resource);
        return $this;
    }


    /**
     * Set error fallback
     *
     * @param string $event
     * @param callable $resource
     * @return $this
     */
    public function when($event, $resource)
    {
        $this->config->events[$event] = $resource;
        return $this;
    }

}