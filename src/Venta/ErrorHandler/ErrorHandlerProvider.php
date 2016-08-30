<?php declare(strict_types = 1);

namespace Venta\ErrorHandler;

use Abava\Container\Contract\Container;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Venta\Contract\ExtensionProvider\{
    Bindings, Errors, Middlewares
};
use Venta\Contract\Kernel;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\RunInterface;

/**
 * Class ErrorHandlerProvider
 *
 * @package Venta\ErrorHandler
 */
class ErrorHandlerProvider implements Bindings, Errors, Middlewares
{

    /**
     * Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Saving Application instance for later use
     *
     * @param Container $container
     * @return void
     */
    public function bindings(Container $container)
    {
        $this->container = $container;

        /*
         * Creating and registering our error handler
         */
        $whoops = new Run();
        $whoops->register();

        /** @var Kernel $kernel */
        $kernel = $container->get(Kernel::class);
        if ($kernel->isCli()) {
            // We don't need pretty pages in cli mode
            $whoops->allowQuit(true);
            $whoops->sendHttpCode(false);
            $whoops->writeToOutput(true);
            $whoops->pushHandler(new PlainTextHandler());
        } else {
            // Push pretty page handler only for local environment
            $whoops->pushHandler(
                $kernel->environment() === 'local' ?
                    new PrettyPageHandler() :
                    new PlainTextHandler()
            );
        }
        /**
         * Bind error handler
         */
        $container->set(RunInterface::class, $whoops, ['error_handler']);

        /*
         * Bind PSR-3 logger
         */
        $container->share(\Monolog\Logger::class, function (Container $c) {
            $logger = new \Monolog\Logger('venta');
            $handler = new \Monolog\Handler\StreamHandler(__DIR__ . '/../storage/logs/app.log');
            $handler->pushProcessor(function ($record) use ($c) {
                /** @var Kernel $kernel */
                $kernel = $c->get(Kernel::class);
                if ($kernel->isCli()) {
                    // Add cli command related extra info
                    /** @var \Symfony\Component\Console\Input\InputInterface $input */
                    $input = $c->get(InputInterface::class);
                    $record['extra']['command'] = $input->getFirstArgument();
                    $record['extra']['arguments'] = $input->getArguments();
                    $record['extra']['options'] = $input->getOptions();
                } else {
                    // Add HTTP request related extra info
                    /** @var \Psr\Http\Message\ServerRequestInterface $request */
                    $request = $c->get(ServerRequestInterface::class);
                    $server = $request->getServerParams();
                    $record['extra']['url'] = $request->getUri()->getPath();
                    $record['extra']['http_method'] = $request->getMethod();
                    $record['extra']['host'] = $request->getUri()->getHost();
                    $record['extra']['referer'] = $request->getHeader('referer');
                    $record['extra']['user_agent'] = $request->getHeader('user-agent');
                    $record['extra']['ip'] = $server['REMOTE_ADDR'] ?? null;
                }
                return $record;
            });
            $handler->setFormatter(new \Monolog\Formatter\LineFormatter());
            $logger->pushHandler($handler);
            return $logger;
        }, ['logger', LoggerInterface::class]);
    }

    /**
     * Pushing default error handlers
     *
     * @param RunInterface $run
     * @return void
     */
    public function errors(RunInterface $run)
    {
        $run->pushHandler($this->container->get(ErrorHandlerLogger::class));
    }

    /**
     * Adding error handling middleware
     *
     * @param MiddlewareCollector $middlewareCollector
     * @return void
     */
    public function middlewares(MiddlewareCollector $middlewareCollector)
    {
        $middlewareCollector->pushMiddleware('error_handler', ErrorHandlerMiddleware::class);
    }

}