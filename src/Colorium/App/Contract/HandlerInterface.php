<?php

namespace Colorium\App\Contract;

use Colorium\App\Context;

interface HandlerInterface
{

    /**
     * Handle app context
     *
     * @param Context $context
     * @return Context
     */
    public function handle(Context $context);

}