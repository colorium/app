<?php

namespace Colorium\App;

use Colorium\Http;
use Colorium\Routing\Contract\RouterInterface;
use Colorium\Templating\Contract\TemplaterInterface;

class Front extends Rest
{

    /**
     * Front app constructor
     *
     * @param RouterInterface $router
     * @param TemplaterInterface $templater
     */
    public function __construct(RouterInterface $router = null, TemplaterInterface $templater = null)
    {
        Kernel::__construct(
            new Plugin\Catching,
            new Plugin\Routing($router),
            new Plugin\Firewall,
            new Plugin\Templating($templater)
        );
    }

}