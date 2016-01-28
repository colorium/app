<?php

namespace Colorium\App\Plugin;

use Colorium\App\Context;
use Colorium\App\Plugin;
use Colorium\Http\Response;
use Colorium\Runtime\Invokable;

class Execution extends Plugin
{

    /**
     * Handle context
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     */
    public function handle(Context $context, callable $chain = null)
    {
        // expect valid invokable
        if(!$context->invokable instanceof Invokable) {
            throw new \RuntimeException('Context::invokable must be a valid Colorium\Runtime\Invokable instance');
        }

        // instanciate invokable class
        $context->invokable->instanciate();

        // add context as last params
        $context->invokable->params[] = $context;

        // execute resource
        $content = $context->invokable->call();
        if($content instanceof Response) {
            $context->response = $content;
            return $context;
        }

        $context->response->raw = true;
        $context->response->content = $content;
        return $context;
    }

}