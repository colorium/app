<?php

namespace Colorium\App;

use Colorium\Routing\Routable;
use Colorium\Templating\Engine;

class Front extends Kernel
{

    /**
     * Front app constructor
     *
     * @param Routable $router
     * @param Engine $templater
     */
    public function __construct(Routable $router = null, Engine $templater = null)
    {
        parent::__construct(
            new Kernel\Wrapping,
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
        $this->router->add($query, $resource);
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

}