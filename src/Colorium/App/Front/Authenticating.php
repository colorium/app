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
        $rank = $context->invokable->annotation('access') ?: 0;
        if($rank) {
            if(Auth::rank() < $rank) {
                throw new Error\Unauthorized;
            }

            $context->rank = Auth::rank();
            $context->user = Auth::user();
        }

        return $chain($context);
    }

}