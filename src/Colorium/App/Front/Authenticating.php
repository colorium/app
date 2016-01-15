<?php

namespace Colorium\App\Front;

use Colorium\App\Context;
use Colorium\App\Plugin;
use Colorium\Http\Error;
use Colorium\Stateful\Auth;

class Authenticating extends Plugin
{

    /**
     * Basic auth process
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     *
     * @throws Error\Unauthorized
     */
    public function handle(Context $context, callable $chain = null)
    {
        // init auth context
        $context->auth = new \stdClass;

        // retrieve user
        if($ref = Auth::ref()) {
            $context->auth->rank = Auth::rank();
            $context->auth->ref = $ref;
            $context->user = Auth::user();
        }

        // access rank needed
        $rank = $context->invokable->annotation('access') ?: 0;
        if($rank and $context->auth->rank < $rank) {
            throw new Error\Unauthorized;
        }

        return $chain($context);
    }

}