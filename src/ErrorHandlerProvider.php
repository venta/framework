<?php declare(strict_types = 1);

namespace Venta\Framework;

use \Psr\Http\Message\{RequestInterface, ResponseInterface};
use Psr\Log\LogLevel;

class ErrorHandlerProvider
{
    /** @var  Application */
    protected $app;

    /**
     * @param Application $app
     */
    public function bindings($app)
    {
        $this->app = $app;
    }

    /**
     * @param \Venta\Routing\MiddlewareCollector $collector
     */
    public function middlewares(\Venta\Routing\MiddlewareCollector $collector)
    {
        $collector->addCallableMiddleware('error_handler', function (RequestInterface $request, \Closure $next) : ResponseInterface {
            try{
                return $next($request);
            }
            catch (\Throwable $e) {
                /** @var \Whoops\RunInterface $run */
                $run = $this->app->make(\Whoops\RunInterface::class);
                /** @var \Psr\Log\LoggerInterface $logger */
                $logger = $this->app->make(\Psr\Log\LoggerInterface::class);
                $logger->log(
                    $e instanceof \Error ? LogLevel::CRITICAL : LogLevel::ERROR,
                    $e->getMessage(),
                    ['exception' => $e]
                );
                $run->allowQuit(false);
                $run->sendHttpCode(false);
                $run->writeToOutput(false);

                // todo Check if we can create our own NEW response or should use binded one

                /** @var ResponseInterface $response */
                $response = $this->app->make('response');
                $response->getBody()->write($run->handleException($e));
                return $response->withStatus($e->getCode() >= 400 ? $e->getCode() : 500);
            }
        });
    }

}