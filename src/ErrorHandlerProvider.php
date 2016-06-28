<?php declare(strict_types = 1);

namespace Venta\Framework;

use \Psr\Http\Message\{RequestInterface, ResponseInterface};

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
        /** @var \Psr\Http\Message\RequestInterface $request */
        $request = $app->make('request');
        $accept = count($request->getHeader('accept')) > 0 ? $request->getHeader('accept')[0] : '';
        /** @todo Add environment check here */
        $app->singleton(
            \Whoops\Handler\HandlerInterface::class,
            preg_match($accept, '/^(application|text)/.*json.*$/') ?
                \Whoops\Handler\JsonResponseHandler::class : \Whoops\Handler\PrettyPageHandler::class
        );
    }

    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        /** @var \Whoops\RunInterface $run */
        $run = $app->make(\Whoops\RunInterface::class);
        /** @var \Whoops\Handler\HandlerInterface $handler */
        $handler = $app->make(\Whoops\Handler\HandlerInterface::class);
        $run->pushHandler($handler);
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
                $run->allowQuit(false);
                $run->sendHttpCode(false);
                $run->writeToOutput(false);
                /** @var ResponseInterface $response */
                $response = $this->app->make('response');
                $response->getBody()->write($run->handleException($e));
                return $response->withStatus($e->getCode() >= 400 ? $e->getCode() : 500);
            }
        });
    }

}