<?php

namespace Colorium\App\Kernel;

use Colorium\App\Context;
use Colorium\App\Plugin;
use Colorium\Http\Response;
use Colorium\Http\Error\NotFoundException;
use Colorium\Routing\Contract\RouterInterface;
use Colorium\Routing\Router;
use Colorium\Runtime;

class Routing extends Plugin
{

    /** @var RouterInterface */
    protected $router;


    /**
     * Create router component
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router = null)
    {
        $this->router = $router ?: new Router;
    }


    /**
     * Setup router
     */
    public function setup()
    {
        $this->app->config->router = &$this->router;
    }


    /**
     * Handle context
     *
     * @param Context $context
     * @param callable $chain
     * @return Response
     *
     * @throws NotFoundException
     */
    public function handle(Context $context, callable $chain = null)
    {
        // ask specific resource
        if($context->forward) {
            list($callable, $params) = $context->forward;
            $context->invokable = $this->resolve($callable, $params);
            $context->forward = null;
        }
        // routing needed
        elseif(!$context->invokable) {
            $query = $context->request->method . ' ' . $context->request->uri->path;
            $route = $this->router->find($query);
            if(!$route) {
                throw new NotFoundException('No route corresponding to query ' . $query);
            }

            $context->route = $route;
            $context->invokable = $this->resolve($route->resource, $route->params);
        }

        return $chain($context);
    }


    /**
     * Resolve invokable
     *
     * @param callable $resource
     * @param array $params
     * @return Runtime\Invokable
     */
    protected function resolve($resource, array $params = [])
    {
        $invokable = Runtime\Resolver::of($resource);
        if(!$invokable) {
            throw new \RuntimeException('callable is not a valid resolvable invokable');
        }

        $invokable->params = $params;
        return $invokable;
    }

}