<?php

namespace Colorium\App;

abstract class Plugin implements Contract\HandlerInterface
{

    /** @var Kernel */
    protected $app;


    /**
     * Bind kernel to plugin
     *
     * @param Kernel $app
     * @return $this
     */
    public function bind(Kernel &$app)
    {
        $this->app = $app;
        return $this;
    }


    /**
     * User setup
     */
    public function setup() {}


    /**
     * Handle app context
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     */
    abstract public function handle(Context $context, callable $chain = null);

}