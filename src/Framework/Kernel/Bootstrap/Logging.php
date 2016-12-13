<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;
use Venta\Contracts\Config\Config;
use Venta\Framework\Kernel\AbstractKernelBootstrap;

/**
 * Class Logging
 *
 * @package Venta\Framework\Kernel\Bootstrap
 */
class Logging extends AbstractKernelBootstrap
{
    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        // todo: implement multi-channel configuration.
        $this->container->bindFactory(LoggerInterface::class, function (Config $config) {

            $handler = new StreamHandler(
                $this->kernel->getRootPath() . '/storage/logs/venta.log',
                $config->log_level ?? Logger::DEBUG
            );

            $handler->pushProcessor(new PsrLogMessageProcessor);

            if (!$this->kernel->isCli()) {
                $handler->pushProcessor(new WebProcessor(null, [ // todo: make list configurable?
                    'url' => 'REQUEST_URI',
                    'ip' => 'REMOTE_ADDR',
                    'http_method' => 'REQUEST_METHOD',
                    'server' => 'SERVER_NAME',
                    'referrer' => 'HTTP_REFERER',
                    'user_agent' => 'HTTP_USER_AGENT',
                ]));
            }

            $logger = new Logger('venta');
            $logger->pushHandler($handler);

            return $logger;

        }, true);
    }
}
