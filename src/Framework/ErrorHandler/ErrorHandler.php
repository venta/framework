<?php declare(strict_types = 1);

namespace Venta\Framework\ErrorHandler;

use ErrorException;
use Throwable;
use Venta\Contracts\ErrorHandler\ErrorHandler as ErrorHandlerContract;

/**
 * Class ErrorHandler
 *
 * @package Venta\Framework\ErrorHandler
 */
class ErrorHandler implements ErrorHandlerContract
{

    /**
     * PHP is shutdown flag.
     *
     * @var bool
     */
    protected $shutdown = false;

    /**
     * ThrowableHandlers to pass caught $throwable for handling.
     *
     * @var HandlerStack
     */
    protected $stack;

    /**
     * Runner constructor registers runner as soon as possible.
     *
     * @param HandlerStack $stack
     */
    public function __construct(HandlerStack $stack)
    {
        $this->stack = $stack;
        $this->register();
    }

    /**
     * @inheritdoc
     * @throws ErrorException
     */
    public function handleError(int $severity, string $message, string $filename, int $lineNumber): bool
    {
        // Check if we should handle this error or ignore it.
        if (!(error_reporting() & $severity)) {
            // Pass error to the next handler.
            return false;
        }

        $exception = new ErrorException($message, 0, $severity, $filename, $lineNumber);
        if ($this->shutdown) {
            // PHP is shutdown, we can't throw exceptions at this time.
            $this->handleThrowable($exception);
        } else {
            // We must throw exception to interrupt further code execution.
            throw $exception;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function handleShutdown()
    {
        $error = error_get_last();
        if ($error && ($error['type'] & self::FATAL)) {
            // Fatal error is not passed to handleError, we need to pass it manually.
            $this->shutdown = true;
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function handleThrowable(Throwable $throwable)
    {
        foreach ($this->stack as $handler) {
            $handler->handle($throwable);
        }
    }

    /**
     * Registers runner as error/exception handler and shutdown function.
     *
     * @return void
     */
    protected function register()
    {
        register_shutdown_function([$this, 'handleShutdown']);
        set_exception_handler([$this, 'handleThrowable']);
        set_error_handler([$this, 'handleError'], error_reporting());
    }

}