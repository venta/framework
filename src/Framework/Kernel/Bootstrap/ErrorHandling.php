<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use Venta\Contracts\Debug\ErrorHandler as ErrorHandlerContract;
use Venta\Contracts\Debug\ErrorRenderer as ErrorRendererContract;
use Venta\Contracts\Debug\ErrorReporterStack as ErrorReporterStackContract;
use Venta\Debug\ErrorHandler;
use Venta\Debug\ErrorReporterStack;
use Venta\Debug\Reporter\ErrorLogReporter;
use Venta\Framework\Debug\Renderer\ConsoleErrorRenderer;
use Venta\Framework\Debug\Renderer\HttpErrorRenderer;
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
    public function __invoke()
    {
        $this->container->bindClass(ErrorHandlerContract::class, ErrorHandler::class, true);

        $this->registerErrorRenderer();
        $this->registerErrorReporters();

        $errorHandler = $this->container->get(ErrorHandler::class);

        register_shutdown_function([$errorHandler, 'handleShutdown']);
        set_exception_handler([$errorHandler, 'handleThrowable']);
        set_error_handler([$errorHandler, 'handleError'], error_reporting());
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

    /**
     * Registers default error reporters.
     */
    private function registerErrorReporters()
    {
        $this->container->bindFactory(
            ErrorReporterStackContract::class,
            function () {
                $reporters = new ErrorReporterStack($this->container);
                $reporters->push(ErrorLogReporter::class);

                return $reporters;
            },
            true
        );
    }

}