<?php

namespace Colorium\App;

interface Handler
{

    /**
     * Handle app context
     *
     * @param Context $context
     * @return Context
     */
    public function handle(Context $context);

}