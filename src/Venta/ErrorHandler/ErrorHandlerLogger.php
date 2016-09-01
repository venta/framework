<?php declare(strict_types = 1);

namespace Venta\ErrorHandler;

use Psr\Log\{
    LoggerInterface, LogLevel
};
use Whoops\Handler\Handler;

/**
 * Class ErrorHandlerLogger
 *
 * @package Venta\ErrorHandler
 */
class ErrorHandlerLogger extends Handler
{

    /**
     * Logger instance
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ErrorHandlerLogger constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logging exception
     *
     * @return int
     */
    public function handle()
    {
        $e = $this->getException();
        $this->logger->log(
            $e instanceof \Error ? LogLevel::CRITICAL : LogLevel::ERROR,
            $e->getMessage(),
            ['exception' => $e]
        );

        return self::DONE;
    }

}