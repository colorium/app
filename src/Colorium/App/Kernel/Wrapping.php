<?php

namespace Colorium\App\Kernel;

use Colorium\App\Context;
use Colorium\App\Plugin;
use Colorium\Http;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Wrapping extends Plugin
{

    /** @var array[] */
    protected $events = [];

    /** @var LoggerInterface */
    protected $logger;

    /** @var bool */
    protected $prod = true;


    /**
     * Init catching component using logger
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger;
    }


    /**
     * Setup catching
     */
    public function setup()
    {
        $this->app->logger = &$this->logger;
        $this->app->events = &$this->events;
        $this->app->prod = &$this->prod;
    }


    /**
     * Handle request/response
     *
     * @param Context $context
     * @param callable $chain
     * @return Http\Response
     *
     * @throws \Exception
     */
    public function handle(Context $context, callable $chain = null)
    {
        try {
            try {
                return $chain($context);
            }
            catch(Http\Error $e) {
                $code = $e->getCode();
                $this->logger->info($code . ': ' . $e->getMessage());
                if(isset($this->events[$code])) {
                    $resource = $this->events[$code];
                    return $this->app->forward($resource, $context);
                }

                throw $e;
            }
        }
        catch(\Exception $e) {
            $this->logger->error($e->getMessage());
            if($this->prod) {
                foreach($this->events as $class => $resource) {
                    if(is_string($class) and $e instanceof $class) {
                        return $this->app->forward($resource, $context);
                    }
                }
            }

            throw $e;
        }
    }

}