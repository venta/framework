<?php declare(strict_types = 1);

namespace Venta\Contracts\ErrorHandler;

use Throwable;

/**
 * Interface Runner
 * Registers as shutdown function, exception handler and error handler.
 *
 * @package Venta\Contracts\ErrorHandler
 */
interface Runner
{

    /**
     * Bitmap value for fatal errors.
     * List of errors that trigger shutdown and have NOT being handled by handleError nor handleThrowable.
     */
    const FATAL = E_ERROR | E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_WARNING;

    /**
     * Handles errors (E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR etc).
     *
     * @param int $errno Error code
     * @param string $errstr Error message
     * @param string $errfile File triggered error
     * @param int $errline Line triggered error
     * @return bool
     */
    public function handleError($errno, $errstr, $errfile, $errline): bool;

    /**
     * Handles php shutdown in case of fatal error (self::FATAL).
     *
     * @return void
     */
    public function handleShutdown();

    /**
     * Handles uncaught throwable (exceptions and errors).
     *
     * @param Throwable $throwable
     * @return void
     */
    public function handleThrowable(Throwable $throwable);

}