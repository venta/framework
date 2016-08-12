<?php declare(strict_types = 1);

namespace Venta\ErrorHandler;

use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Venta\Contract\Application;
use Venta\Contract\ExtensionProvider\{
    Bindings, Errors, Middlewares
};
use Whoops\RunInterface;

/**
 * Class ErrorHandlerProvider
 *
 * @package Venta\ErrorHandler
 */
class ErrorHandlerProvider implements Bindings, Errors, Middlewares
{

    /**
     * Application instance
     *
     * @var Application
     */
    protected $app;

    /**
     * Saving Application instance for later use
     *
     * @param Application $app
     * @return void
     */
    public function bindings(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Pushing default error handlers
     *
     * @param RunInterface $run
     * @return void
     */
    public function errors(RunInterface $run)
    {
        $run->pushHandler($this->app->make(ErrorHandlerLogger::class));
    }

    /**
     * Adding error handling middleware
     *
     * @param MiddlewareCollector $middlewareCollector
     * @return void
     */
    public function middlewares(MiddlewareCollector $middlewareCollector)
    {
        $middlewareCollector->pushMiddleware('error_handler', ErrorHandlerMiddleware::class);
    }

}