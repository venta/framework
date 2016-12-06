<?php declare(strict_types = 1);

namespace Venta\Contracts\ErrorHandler;

/**
 * Interface ErrorHandlerAware
 *
 * @package Venta\Contracts\ErrorHandler
 */
interface ErrorHandlerAware
{
    /**
     * Injects error handler instance.
     *
     * @param ErrorHandler $errorHandler
     * @return mixed
     */
    public function setErrorHandler(ErrorHandler $errorHandler);
}