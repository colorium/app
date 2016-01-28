<?php

namespace Colorium\App\Plugin;

use Colorium\App\Context;
use Colorium\App\Plugin;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;

class Rendering extends Plugin
{

    /**
     * Handle request/response
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     */
    public function handle(Context $context, callable $chain = null)
    {
        $context = $chain($context);

        // expect valid response
        if(!$context->response instanceof Response) {
            throw new \RuntimeException('Context::response must be a valid Colorium\Http\Response instance');
        }
        // render redirect
        elseif($context->response instanceof Response\Redirect and $context->response->uri[0] == '/') {
            $context->response->uri = $context->request->uri->make($context->response->uri);
        }
        // render default as json
        elseif($context->response->raw) {
            $context->response = new Response\Json($context->response->content, $context->response->code);
        }

        return $context;
    }

}