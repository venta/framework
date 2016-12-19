<?php declare(strict_types = 1);

namespace Venta\Debug\Reporter;

use Error;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Throwable;
use Venta\Contracts\Debug\ErrorReporter;

/**
 * Class LogErrorReporter
 *
 * @package Venta\Debug\Reporter
 */
final class LogErrorReporter implements ErrorReporter
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ErrorLogReporter constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function report(Throwable $e)
    {
        $this->logger->log(
            $e instanceof Error ? LogLevel::CRITICAL : LogLevel::ERROR,
            $e->getMessage(),
            ['exception' => $e]
        );
    }

}