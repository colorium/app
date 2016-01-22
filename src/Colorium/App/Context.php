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

    /** @var Http\Response */
    public $response;

    /** @var Context */
    public $parent;


    /**
     * Create new context
     *
     * @param Http\Request $request
     * @param Http\Response $response
     * @param Context $parent
     */
    public function __construct(Http\Request $request, Http\Response $response, Context $parent = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->parent = $parent;
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
     * @param string $key
     * @return string
     */
    public function post($key)
    {
        return $this->request->value($key);
    }


    /**
     * Create sub-context
     *
     * @return Context
     */
    public function sub()
    {
        $sub = clone $this;
        $sub->parent = $this;

        return $sub;
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