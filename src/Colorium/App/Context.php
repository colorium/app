<?php

namespace Colorium\App;

use Colorium\Http;
use Colorium\Routing;
use Colorium\Runtime;

class Context extends \stdClass
{

    /** @var Http\Request */
    public $request;

    /** @var Routing\Route */
    public $route;

    /** @var Runtime\Invokable */
    public $invokable;

    /** @var Plugin\Firewall\Access */
    public $access;

    /** @var Http\Response */
    public $response;

    /** @var array */
    public $forward = [];

    /** @var callable */
    public $handler;


    /**
     * Create new context
     *
     * @param Http\Request $request
     * @param Http\Response $response
     */
    public function __construct(Http\Request $request, Http\Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    /**
     * Uri helper
     *
     * @param ...$parts
     * @return string
     */
    public function uri(...$parts)
    {
        $uri = implode('/', $parts);
        return (string)$this->request->uri->make($uri);
    }


    /**
     * Return authentified user
     *
     * @return object
     */
    public function user()
    {
        return isset($this->access->user)
            ? $this->access->user
            : null;
    }


    /**
     * Get post value
     *
     * @param array $keys
     * @return string
     */
    public function post(...$keys)
    {
        if(!$keys) {
            return $this->request->values;
        }
        elseif(count($keys) === 1) {
            return $this->request->value($keys[0]);
        }

        $values = [];
        foreach($keys as $key) {
            $values[] = $this->request->value($key);
        }
        return $values;
    }


    /**
     * Set forward instruction
     *
     * @param callable $resource
     * @param ...$params
     * @return $this
     */
    public function resource($resource, ...$params)
    {
        $this->forward = [$resource, $params];

        return $this;
    }


    /**
     * Forward context
     *
     * @param callable $resource
     * @param ...$params
     * @return Context
     */
    public function forward($resource = null, ...$params)
    {
        // sub context
        $context = clone $this;
        if($resource) {
            $context->resource($resource, ...$params);
        }

        // clean master context
        $this->forward = null;

        return call_user_func($this->handler, $context);
    }


    /**
     * Terminate context
     *
     * @return string
     */
    public function end()
    {
        return $this->response->send();
    }

}