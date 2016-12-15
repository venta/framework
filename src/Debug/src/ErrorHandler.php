<?php declare(strict_types = 1);

namespace Venta\Debug;

use ErrorException;
use Throwable;
use Venta\Contracts\Debug\ErrorHandler as ErrorHandlerContract;
use Venta\Contracts\Debug\ErrorRenderer;
use Venta\Contracts\Debug\ErrorReporterStack;

/**
 * Class ErrorHandler
 *
 * @package Venta\Debug
 */
final class ErrorHandler implements ErrorHandlerContract
{
    /**
     * Controls whether error handler can throw exceptions.
     * E.g. shutdown handler cannot produce new exceptions.
     *
     * @var bool
     */
    private $canThrowException = true;

    /**
     * The error renderer.
     *
     * @var ErrorRenderer
     */
    private $renderer;

    /**
     * The error reporter pipeline.
     *
     * @var ErrorReporterStack
     */
    private $reporters;

    /**
     * ErrorHandler constructor.
     *
     * @param ErrorRenderer $renderer
     * @param ErrorReporterStack $reporters
     */
    public function __construct(ErrorRenderer $renderer, ErrorReporterStack $reporters)
    {
        $this->renderer = $renderer;
        $this->reporters = $reporters;
    }

    /**
     * @inheritdoc
     * @throws ErrorException
     */
    public function handleError(int $severity, string $message, string $filename, int $line): bool
    {
        if (!$this->isSevereEnough($severity)) {
            // Pass error to the next handler.
            return false;
        }

        $exception = new ErrorException($message, 0, $severity, $filename, $line);
        if ($this->canThrowException) {
            throw $exception;
        }

        $this->handleThrowable($exception);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function handleShutdown()
    {
        $this->canThrowException = false;
        $error = error_get_last();
        if ($error && ($error['type'] & self::FATAL)) {
            // Fatal error is not passed to handleError, we need to pass it manually.
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function handleThrowable(Throwable $e)
    {
        foreach ($this->reporters as $reporter) {
            $reporter->report($e);
        }

        $this->renderer->render($e);
    }

    /**
     * Checks if the error is severe enough and must be handled.
     *
     * @param int $severity
     * @return bool
     */
    private function isSevereEnough(int $severity)
    {
        return error_reporting() & $severity;
    }

}