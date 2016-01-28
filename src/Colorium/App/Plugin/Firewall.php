<?php

namespace Colorium\App\Plugin;

use Colorium\App\Context;
use Colorium\App\Plugin;
use Colorium\Http\Error\AccessDeniedException;
use Colorium\Stateful\Auth;

class Firewall extends Plugin
{

    /**
     * Basic auth process
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     *
     * @throws AccessDeniedException
     */
    public function handle(Context $context, callable $chain = null)
    {
        // define access report
        $context->access = new Firewall\Access(true, 0, 0);

        // access control needed
        $context->access->level = $context->invokable->annotation('access');
        if($context->access->level and !Auth::rank($context->access->level)) {
            $context->access->granted = false;
            throw new AccessDeniedException;
        }

        // update context
        $context->access->auth = Auth::valid();
        $context->access->user = Auth::user();

        return $chain($context);
    }

}