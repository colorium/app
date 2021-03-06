<?php

namespace Colorium\App;

abstract class Plugin implements Contract\PluginInterface
{

    /**
     * Bind to app
     *
     * @param Kernel $app
     */
    public function bind(Kernel &$app) {}

}