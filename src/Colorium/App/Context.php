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
     * Forward context
     *
     * @param $resource
     * @param ...$params
     * @return Context
     */
    public function forward($resource, ...$params)
    {
        $context = clone $this;
        $context->forward = [$resource, $params];
        $context->invokable = null;

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


    /**
     * Generate redirect response
     *
     * @param string $uri
     * @param int $code
     * @param array $headers
     * @return Http\Response\Redirect
     */
    public static function redirect($uri, $code = 302, array $headers = [])
    {
        return new Http\Response\Redirect($uri, $code, $headers);
    }


    /**
     * Generate json response
     *
     * @param $content
     * @param int $code
     * @param array $headers
     * @return Http\Response\Json
     */
    public static function json($content, $code = 302, array $headers = [])
    {
        return new Http\Response\Json($content, $code, $headers);
    }


    /**
     * Generate template response
     *
     * @param string $template
     * @param array $vars
     * @param int $code
     * @param array $headers
     * @return Http\Response\Template
     */
    public static function template($template, array $vars = [], $code = 200, array $headers = [])
    {
        return new Http\Response\Template($template, $vars, $code, $headers);
    }

}