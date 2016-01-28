<?php

namespace Colorium\App;

use Colorium\Http;
use Colorium\Runtime;
use Colorium\App\Contract\PluginInterface;
use Colorium\App\Contract\HandlerInterface;

class Kernel extends \stdClass implements HandlerInterface
{

    /** @var PluginInterface[] */
    protected $plugins = [];


    /**
     * Kernel constructor
     *
     * @param PluginInterface $plugins
     */
    public function __construct(PluginInterface ...$plugins)
    {
        $this->plugins = $plugins;
        $this->plugins[] = new Plugin\Execution;
        foreach($this->plugins as $plugin) {
            $plugin->bind($this);
        }
    }


    /**
     * Handle app context
     *
     * @param Context $context
     * @return Context
     */
    public function handle(Context $context)
    {
        // setup plugins
        $context->handler = [$this, 'handle'];
        foreach($this->plugins as $plugin) {
            $plugin->setup($context);
        }

        // stack plugins
        $stack = [];
        $plugins = array_reverse($this->plugins);
        foreach($plugins as $plugin) {
            $chain = end($stack) ?: null;
            $stack[] = function(Context $context) use($plugin, $chain) {
                return $plugin->handle($context, $chain);
            };
        }

        // trigger chain
        $stack = array_reverse($stack);
        $first = reset($stack);
        return call_user_func($first, $context);
    }


    /**
     * Run kernel
     *
     * @param Context $context
     * @return Context
     */
    public function run(Context $context = null)
    {
        $context = $context ?: static::context();
        $context = $this->handle($context);

        return $context;
    }


    /**
     * Generate context
     *
     * @return Context
     */
    public static function context()
    {
        $request = Http\Request::current();
        $response = new Http\Response;

        return new Context($request, $response);
    }

}