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
            new Plugin\Catching,
            new Plugin\Routing($router),
            new Plugin\Rendering
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
        $this->router->add($query, $resource);

        return $this;
    }


    /**
     * Set multiple routes
     *
     * @param array $routes
     * @return $this
     */
    public function routes(array $routes)
    {
        foreach($routes as $query => $resource) {
            $this->router->add($query, $resource);
        }

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
        $this->events[$event] = $resource;
        return $this;
    }


    /**
     * Set multiple error fallback
     *
     * @param array $events
     * @return $this
     */
    public function events(array $events)
    {
        $this->events = $events + $this->config->events;

        return $this;
    }

}