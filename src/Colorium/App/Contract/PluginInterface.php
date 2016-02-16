<?php

namespace Colorium\App\Contract;

use Colorium\App\Kernel;
use Colorium\App\Context;

interface PluginInterface extends HandlerInterface
{

    /**
     * Bind to app
     *
     * @param Kernel $app
     */
    public function bind(Kernel &$app);

    /**
     * Handle context
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     */
    public function handle(Context $context, callable $chain = null);

}