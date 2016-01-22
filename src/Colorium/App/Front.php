<?php

namespace Colorium\App;

use Colorium\Routing\Contract\RouterInterface;
use Colorium\Templating\Contract\TemplaterInterface;

class Front extends Kernel
{

    /**
     * Front app constructor
     *
     * @param RouterInterface $router
     * @param TemplaterInterface $templater
     */
    public function __construct(RouterInterface $router = null, TemplaterInterface $templater = null)
    {
        parent::__construct(
            new Kernel\Catching,
            new Kernel\Routing($router),
            new Front\Authenticating,
            new Front\Templating($templater)
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
     * Set multiple routes
     *
     * @param array $routes
     * @return $this
     */
    public function routes(array $routes)
    {
        foreach($routes as $query => $resource) {
            $this->config->router->add($query, $resource);
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
        $this->config->events[$event] = $resource;
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
        $this->config->events = array_merge($this->config->events, $events);

        return $this;
    }

}