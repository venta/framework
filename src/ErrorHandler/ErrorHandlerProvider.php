<?php declare(strict_types = 1);

namespace Venta\Framework\ErrorHandler;

use Abava\Routing\MiddlewareCollector;
use Venta\Framework\Contracts\ApplicationContract;
use Venta\Framework\Contracts\ExtensionProvider\{
    BindingsContract, ErrorsContract, MiddlewaresContract
};
use Whoops\RunInterface;

/**
 * Class ErrorHandlerProvider
 *
 * @package Venta\Framework\ErrorHandler
 */
class ErrorHandlerProvider implements BindingsContract, ErrorsContract, MiddlewaresContract
{

    /**
     * Application instance
     *
     * @var ApplicationContract
     */
    protected $app;

    /**
     * Saving Application instance for later use
     *
     * @param ApplicationContract $app
     * @return void
     */
    public function bindings(ApplicationContract $app)
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
        $middlewareCollector->addMiddleware('error_handler', $this->app->make(ErrorHandlerMiddleware::class));
    }

}