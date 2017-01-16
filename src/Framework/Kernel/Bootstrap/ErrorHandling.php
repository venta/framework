<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use Venta\Contracts\Debug\ErrorHandler as ErrorHandlerContract;
use Venta\Contracts\Debug\ErrorRenderer as ErrorRendererContract;
use Venta\Contracts\Debug\ErrorReporterAggregate as ErrorReporterAggregateContract;
use Venta\Debug\ErrorHandler;
use Venta\Debug\ErrorReporterAggregate;
use Venta\Debug\Reporter\LogErrorReporter;
use Venta\Framework\Debug\Renderer\ConsoleErrorRenderer;
use Venta\Framework\Debug\Renderer\HttpErrorRenderer;
use Venta\Framework\Kernel\AbstractKernelBootstrap;

/**
 * Class ErrorHandling
 *
 * @package Venta\Framework\Kernel\Bootstrap
 */
final class ErrorHandling extends AbstractKernelBootstrap
{
    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        $this->container()->bind(ErrorHandlerContract::class, ErrorHandler::class);

        $this->registerErrorRenderer();
        $this->registerErrorReporters();

        $errorHandler = $this->container()->get(ErrorHandler::class);

        register_shutdown_function([$errorHandler, 'handleShutdown']);
        set_exception_handler([$errorHandler, 'handleThrowable']);
        set_error_handler([$errorHandler, 'handleError'], error_reporting());
    }

    /**
     * Registers the default error renderer.
     */
    private function registerErrorRenderer()
    {
        if ($this->kernel()->isCli()) {
            $this->container()->bind(ErrorRendererContract::class, ConsoleErrorRenderer::class);
        } else {
            $this->container()->bind(ErrorRendererContract::class, HttpErrorRenderer::class);
        }
    }

    /**
     * Registers default error reporters.
     */
    private function registerErrorReporters()
    {
        $this->container()->factory(
            ErrorReporterAggregateContract::class,
            function () {
                $reporters = new ErrorReporterAggregate($this->container());
                $reporters->push(LogErrorReporter::class);

                return $reporters;
            },
            true
        );
    }

}