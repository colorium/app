<?php

namespace Colorium\App;

use Colorium\Routing\Routable;

class Rest extends Kernel
{

    /**
     * Rest app constructor
     *
     * @param Routable $router
     */
    public function __construct(Routable $router = null)
    {
        parent::__construct(
            new Kernel\Wrapping,
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