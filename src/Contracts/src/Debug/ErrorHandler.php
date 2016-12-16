<?php declare(strict_types = 1);

namespace Venta\Contracts\Debug;

use Throwable;

/**
 * Interface Debug
 * Registers as shutdown function, exception handler and error handler.
 *
 * @package Venta\Contracts\Debug
 */
interface ErrorHandler
{

    /**
     * Bitmap value for fatal errors.
     * List of errors that trigger shutdown and have NOT being handled by handleError nor handleThrowable.
     */
    const FATAL = E_ERROR | E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_WARNING;

    /**
     * Handles errors (E_USER_ERROR | E_COMPILE_ERROR | E_CORE_ERROR etc).
     *
     * @param int $severity
     * @param string $message
     * @param string $filename
     * @param int $line
     * @return bool
     */
    public function handleError(int $severity, string $message, string $filename, int $line): bool;

    /**
     * Handles php shutdown in case of fatal error (self::FATAL).
     *
     * @return void
     */
    public function handleShutdown();

    /**
     * Handles uncaught throwable (exceptions and errors).
     *
     * @param Throwable $e
     * @return void
     */
    public function handleThrowable(Throwable $e);

}