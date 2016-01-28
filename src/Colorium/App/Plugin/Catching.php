<?php

namespace Colorium\App\Plugin;

use Colorium\App\Context;
use Colorium\App\Kernel;
use Colorium\App\Plugin;
use Colorium\Http\Error\HttpException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Catching extends Plugin
{

    /** @var array[] */
    protected $events = [];

    /** @var LoggerInterface */
    protected $logger;

    /** @var bool */
    protected $catch = true;


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
     * Bind to app
     *
     * @param Kernel $app
     */
    public function bind(Kernel &$app)
    {
        $app->logger = &$this->logger;
        $app->events = &$this->events;
        $app->catch = &$this->catch;
    }


    /**
     * Handle request/response
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     *
     * @throws \Exception
     */
    public function handle(Context $context, callable $chain = null)
    {
        return $this->catch
            ? $this->catchGlobal($context, $chain)
            : $chain($context);
    }


    /**
     * Catch global exception
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     *
     * @throws \Exception
     */
    protected function catchGlobal(Context $context, callable $chain)
    {
        try {
            return $this->catchHttp($context, $chain);
        }
        catch(\Exception $e) {

            // log error
            $this->logger->error(get_class($e) . ': ' . $e->getMessage());

            // find class event
            foreach($this->events as $class => $resource) {
                if(is_string($class) and $e instanceof $class) {
                    return $context->forward($resource, $context, $e);
                }
            }

            // uncaught, re-throw
            throw $e;
        }
    }


    /**
     * Catch HTTP Exception
     *
     * @param Context $context
     * @param callable $chain
     * @return Context
     *
     * @throws HttpException
     * @throws \Exception
     */
    protected function catchHttp(Context $context, callable $chain)
    {
        try {
            return $chain($context);
        }
        catch(HttpException $e) {

            // get http code
            $code = $e->getCode();

            // log http info
            $this->logger->info('HTTP ' . $code . ': ' . $e->getMessage());

            // find code event
            if(isset($this->events[$code])) {
                $resource = $this->events[$code];
                return $context->forward($resource, $context, $e);
            }

            // uncaught, re-throw (and caught by catchGlobal)
            throw $e;
        }
    }

}