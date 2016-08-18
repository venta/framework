<?php declare(strict_types = 1);

namespace Venta\Application;

use Abava\Container\Contract\Container;
use Abava\Http\Contract\Emitter;
use Abava\Routing\Contract\Collector;
use Abava\Routing\Contract\Matcher;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Abava\Routing\Contract\Middleware\Pipeline;
use Abava\Routing\Contract\Strategy;
use Abava\Routing\Route;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contract\Kernel;

/**
 * Class HttpApplication
 *
 * @package Venta
 */
class HttpApplication implements \Venta\Contract\Application\HttpApplication
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * HttpApplication constructor.
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->container = $kernel->boot();
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Making request
        |--------------------------------------------------------------------------
        |
        | Getting ServerRequest instance from container
        */
        /** @var ServerRequestInterface $request */
        $request = $this->container->get(ServerRequestInterface::class);

        /*
        |--------------------------------------------------------------------------
        | Matching route
        |--------------------------------------------------------------------------
        |
        | Find route matching provided request instance
        */
        /** @var Matcher $matcher */
        $matcher = $this->container->get(Matcher::class);
        /** @var Collector $routeCollector */
        $routeCollector = $this->container->get(Collector::class);
        $route = $matcher->match($request, $routeCollector);

        /*
        |--------------------------------------------------------------------------
        | Binding route
        |--------------------------------------------------------------------------
        |
        | Binding current route instance to container for later use
        */
        $this->container->singleton('route', $route);
        $this->container->singleton(Route::class, $route);

        /*
        |--------------------------------------------------------------------------
        | Pushing route middlewares
        |--------------------------------------------------------------------------
        |
        | Route may have its own middlewares. Push them to the end of
        | the middleware stack
        */
        /** @var MiddlewareCollector $middlewareCollector */
        $middlewareCollector = $this->container->get(MiddlewareCollector::class);
        foreach ($route->getMiddlewares() as $name => $middleware) {
            $middlewareCollector->pushMiddleware($name, $middleware);
        }

        /*
        |--------------------------------------------------------------------------
        | Handle route action
        |--------------------------------------------------------------------------
        |
        | Create last middleware from route callable (action) using strategy
        */
        /** @var Strategy $strategy */
        $strategy = $this->container->get(Strategy::class);
        $last = function () use ($strategy, $route) {
            return $strategy->dispatch($route);
        };

        /*
        |--------------------------------------------------------------------------
        | Make middleware pipeline
        |--------------------------------------------------------------------------
        |
        | Pass request to middleware pipeline to get response in return
        */
        /** @var Pipeline $middlewarePipeline */
        $middlewarePipeline = $this->container->get(Pipeline::class);
        $response = $middlewarePipeline->handle($request, $last);

        /*
        |--------------------------------------------------------------------------
        | Emit response
        |--------------------------------------------------------------------------
        |
        | Emit response to the client - send headers and body
        */
        /** @var Emitter $emitter */
        $emitter = $this->container->get(Emitter::class);
        $emitter->emit($response);
    }

}