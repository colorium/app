<?php

namespace Colorium\App;

use Colorium\Http;

class Kernel extends Plugin implements Handler
{

    /** @var Context */
    public $context;

    /** @var callable[] */
    protected $plugins = [];


    /**
     * Kernel constructor
     *
     * @param Plugin ...$plugins
     */
    public function __construct(Plugin ...$plugins)
    {
        $request = Http\Request::current();
        $response = new Http\Response;
        $this->context = new Context($request, $response);

        $plugins[] = new Kernel\Execution;
        $plugins = array_reverse($plugins);
        foreach($plugins as $plugin) {
            $this->plug($plugin);
        }
    }


    /**
     * Attach plugin
     *
     * @param Plugin $plugin
     */
    protected function plug(Plugin $plugin)
    {
        $chain = reset($this->plugins) ?: null;
        $plugin->bind($this)->setup();
        $callable = function(Context $context) use($plugin, $chain) {
            return $plugin->handle($context, $chain);
        };
        array_unshift($this->plugins, $callable);
    }


    /**
     * Handle app context
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     */
    public function handle(Context $context, callable $chain = null)
    {
        $stack = reset($this->plugins);
        $context = call_user_func($stack, $context);

        return $chain
            ? $chain($context)
            : $context;
    }


    /**
     * Run kernel
     *
     * @param Context $context
     * @return Context
     */
    public function run(Context $context = null)
    {
        $context = $context ?: $this->context;
        $context = $this->handle($context);

        return $context;
    }


    /**
     * Forward directly to invokable
     *
     * @param $resource
     * @param ...$params
     * @return Context
     */
    public function forward($resource, ...$params)
    {
        $context = $this->context->child();
        $context->forward = [$resource, $params];
        $context->invokable = null;

        return $this->run($context);
    }

}