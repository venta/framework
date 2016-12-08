<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Debug\ErrorHandler as ErrorHandlerContract;
use Venta\Contracts\Debug\ErrorRenderer as ErrorRendererContract;
use Venta\Contracts\Debug\ErrorReporterStack as ErrorReporterStackContract;
use Venta\Debug\ErrorHandler;
use Venta\Debug\ErrorReporterStack;
use Venta\Debug\Renderer\ConsoleErrorRenderer;
use Venta\Debug\Renderer\HttpErrorRenderer;
use Venta\Debug\Reporter\ErrorLogReporter;
use Venta\Framework\Kernel\AbstractKernelBootstrap;

/**
 * Class ErrorHandling
 *
 * @package Venta\Framework\Kernel\Bootstrap
 */
class ErrorHandling extends AbstractKernelBootstrap
{
    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container->bindClass(ErrorHandlerContract::class, ErrorHandler::class, true);

        $this->registerErrorRenderer();
        $this->registerErrorReporters();

        $errorHandler = $this->getErrorHandler();
        register_shutdown_function([$errorHandler, 'handleShutdown']);
        set_exception_handler([$errorHandler, 'handleThrowable']);
        set_error_handler([$errorHandler, 'handleError'], error_reporting());
    }

    /**
     * Returns error handler contract implementation.
     *
     * @return ErrorHandlerContract
     */
    private function getErrorHandler(): ErrorHandlerContract
    {
        $factory = new LazyLoadingValueHolderFactory;
        $initializer = function (
            &$wrappedObject, LazyLoadingInterface $proxy, $method, array $parameters, &$initializer
        ) {
            $initializer = null; // disable initialization.
            $wrappedObject = $this->container->get(ErrorHandler::class);

            return true; // confirm that initialization occurred correctly.
        };

        return $factory->createProxy(ErrorHandlerContract::class, $initializer);
    }

    /**
     * Registers default error reporters.
     */
    private function registerErrorReporters()
    {
        $this->container->bindFactory(ErrorReporterStackContract::class, function(Container $container) {
            $reporters = new ErrorReporterStack($container);
            $reporters->push(ErrorLogReporter::class);

            return $reporters;
        }, true);
    }

    /**
     * Registers the default error renderer.
     */
    private function registerErrorRenderer()
    {
        if ($this->kernel->isCli()) {
            $this->container->bindClass(ErrorRendererContract::class, ConsoleErrorRenderer::class, true);
        } else {
            $this->container->bindClass(ErrorRendererContract::class, HttpErrorRenderer::class, true);
        }
    }

}